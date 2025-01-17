<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Approve;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\ApprovalNotification;
use App\Models\User;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Carbon;

class ApproveController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth'); // Pastikan middleware ini digunakan
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // Progress modal data
            if ($request->type === 'progress') {
                $query = DB::table('t_approval_report')
                    ->select([
                        'id_capex',
                        'project_desc',
                        'wbs_capex',
                        'upload_by',
                        'status_capex',
                        'status_approve_1',
                        'status_approve_2',
                        'status_approve_3',
                        'status_approve_4',
                        'upload_date',
                    ])
                    ->where('status', 1);

                return DataTables::of($query)
                    ->addIndexColumn()
                    ->make(true);
            }
            // Summary data for progress modal
            elseif ($request->type === 'summary') {
                $data = DB::table('t_approval_report')
                    ->select(
                        DB::raw('COUNT(*) as total'),
                        DB::raw('SUM(CASE 
                        WHEN wbs_capex = "Project" AND status_approve_1 = 1 AND status_approve_2 = 1 AND status_approve_3 = 1 AND status_approve_4 = 1 THEN 1
                        WHEN wbs_capex = "Non Project" AND status_approve_1 = 1 AND status_approve_2 = 1 AND status_approve_4 = 1 THEN 1
                        ELSE 0 END) as completed'),
                        DB::raw('SUM(CASE 
                        WHEN wbs_capex = "Project" AND (status_approve_1 = 0 OR status_approve_2 = 0 OR status_approve_3 = 0 OR status_approve_4 = 0) THEN 1
                        WHEN wbs_capex = "Non Project" AND (status_approve_1 = 0 OR status_approve_2 = 0 OR status_approve_4 = 0) THEN 1
                        ELSE 0 END) as in_progress')
                    )
                    ->first();

                return response()->json([
                    'total' => $data->total,
                    'completed' => $data->completed,
                    'in_progress' => $data->in_progress,
                ]);
            }
            // Main approval table data
            else {
                $query = Approve::getData();
                return DataTables::of($query)
                    ->addColumn('action', function ($row) {
                        return view('approve/datatables/actionbtn', [
                            'row' => $row,
                        ]);
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
        }

        return view('approve.index');
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    private function sendEmailToNextApprover($capexData, $currentRole, $currentStatus)
    {
        try {
            if ($currentRole === 'admin' && auth::id() == 3 && $currentStatus == 1) {
                $nextUser = User::find(4);
                if ($nextUser) {
                    Mail::to($nextUser->email)->send(new ApprovalNotification($capexData, $nextUser));
                }
            } else if ($currentRole === 'admin' && auth::id() == 4 && $currentStatus == 1) {
                $nextUser = User::whereHas('roles', function ($query) {
                    $query->where('name', 'user');
                })->first();

                if ($nextUser) {
                    Mail::to($nextUser->email)->send(new ApprovalNotification($capexData, $nextUser));
                }
            } else if ($currentRole === 'user' && $currentStatus == 1) {
                // Jika user approve dan WBS adalah Project, kirim ke engineer
                if ($capexData['wbs_capex'] === 'Project') {
                    // Menggunakan Laratrust untuk mencari user dengan role 'engineer'
                    $nextUser = User::whereHas('roles', function ($query) {
                        $query->where('name', 'engineering');
                    })->first();

                    if ($nextUser) {
                        Mail::to($nextUser->email)->send(new ApprovalNotification($capexData, $nextUser));
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error('Error sending email: ' . $e->getMessage());
            throw $e; // Re-throw exception agar bisa ditangkap di controller utama
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $flag = $request->input('flag');

        if ($flag === 'upload-pdf') {
            // Validasi input
            $request->validate([
                'id_capex' => 'required|exists:t_approval_report,id_capex',
                'file_pdf' => 'required|file|mimes:pdf|max:2048',
            ]);

            $idCapex = $request->input('id_capex');

            // Cari data pada t_approval_report
            $approve = DB::table('t_approval_report')->where('id_capex', $idCapex)->first();

            if (!$approve) {
                return response()->json(['error' => 'Data capex tidak ditemukan di approval report'], 404);
            }

            // Proses upload file PDF
            if ($request->hasFile('file_pdf')) {
                $PDFfile = $request->file('file_pdf');
                $PDFfileName = $PDFfile->getClientOriginalName();

                // Cek apakah nama file sudah ada di database
                $fileExists = DB::table('t_approval_report')->where('file_pdf', $PDFfileName)->exists();

                if ($fileExists) {
                    return response()->json(['error' => 'Nama file sudah digunakan. Harap gunakan nama file yang berbeda.'], 400);
                }
                // Simpan file original
                $PDFfile->storeAs('uploads/approvalFiles', $PDFfileName, 'public');

                // Update database
                $updateData = [
                    'file_pdf' => $PDFfileName,
                    'upload_by' => Auth::user()->name,
                    'upload_date' => now(),
                    'updated_at' => now(),
                    'date' => $request->input('date'),
                    'wbs_type' => $request->input('wbs_type'),
                    'engineering_production' => $request->boolean('engineering'),
                    'maintenance' => $request->boolean('maintenance'),
                    'outstanding_inventory' => $request->boolean('outstanding_inventory'),
                    'material' => $request->boolean('material'),
                    'jasa' => $request->boolean('jasa'),
                    'etc' => $request->boolean('etc'),
                    'warehouse_received' => $request->boolean('warehouse'),
                    'user_received' => $request->boolean('user'),
                    'berita_acara' => $request->boolean('berita_acara'),
                    'remark' => $request->input('remark'),
                ];

                // Update database
                DB::table('t_approval_report')
                    ->where('id_capex', $idCapex)
                    ->update($updateData);

                return response()->json([
                    'success' => 'File PDF berhasil diunggah dan ditandatangani',
                    'file_path' => 'uploads/approvalFiles/' . $PDFfileName
                ]);
            }

            return response()->json(['error' => 'File PDF tidak ditemukan'], 400);
        } else if ($flag === 'signature') {
            $userRole = auth::user()->roles->first()->name;
            $userId = auth::id();

            // Validasi sesuai role
            $statusField = 'status_approve_1';
            if ($userRole === 'user') {
                $statusField = 'status_approve_2';
            } else if ($userRole === 'engineering') {
                $statusField = 'status_approve_3';
            } else if ($userRole === 'admin' && $userId === 4) {
                $statusField = 'status_approve_4';
            }

            $request->validate([
                'id_capex' => 'required|exists:t_approval_report,id_capex',
                $statusField => 'required|in:1,2',
            ]);

            try {
                $idCapex = $request->input('id_capex');
                $statusApprove = $request->input($statusField);

                // Tambahkan logika approval berdasarkan role
                $updateData = [];
                if ($userRole === 'admin') {
                    if ($userId == 3) {
                        $updateData['approved_by_admin_1'] = auth::user()->name;
                        $updateData['approved_at_admin_1'] = now()->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s');
                        $updateData['status_approve_1'] = $statusApprove;
                    } else if ($userId == 4) {
                        $updateData['approved_by_admin_2'] = auth::user()->name;
                        $updateData['approved_at_admin_2'] = now()->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s');
                        $updateData['status_approve_4'] = $statusApprove;
                    }

                    // Reset approval berikutnya jika disapprove
                    if ($statusApprove == 2) {
                        $updateData['status_approve_2'] = 0;
                        $updateData['status_approve_3'] = 0;
                        $updateData['approved_by_user'] = null;
                        $updateData['approved_by_engineer'] = null;
                    }
                } else if ($userRole === 'user') {
                    $updateData['approved_by_user'] = auth::user()->name;
                    $updateData['approved_at_user'] = now()->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s');
                    $updateData['status_approve_2'] = $statusApprove;

                    if ($statusApprove == 2) {
                        $updateData['status_approve_3'] = 0;
                        $updateData['approved_by_engineer'] = null;
                    }
                } else if ($userRole === 'engineering') {
                    $updateData['approved_by_engineer'] = auth::user()->name;
                    $updateData['approved_at_engineer'] = now()->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s');
                    $updateData['status_approve_3'] = $statusApprove;
                }

                // Proses tanda tangan jika approve
                if ($statusApprove == 1) {
                    $approvalReport = Approve::with('capex')
                        ->where('id_capex', $idCapex)
                        ->first();

                    $data = $approvalReport->toArray();

                    // Generate form detail
                    $detailFile = $this->generateFromTemplate('detail', $data);

                    // Generate form closing
                    $closingFile = $this->generateFromTemplate('closing', $data);

                    // Generate form acceptance
                    $acceptanceFile = $this->generateFromTemplate('acceptance', $data);

                    // Update nama file PDF
                    $updateData['signature_detail_file'] = basename($detailFile);
                    $updateData['signature_closing_file'] = basename($closingFile);
                    $updateData['signature_acceptance'] = basename($acceptanceFile);

                    $this->sendEmailToNextApprover($data, $userRole, $statusApprove);
                } else if ($statusApprove == 2) {  // Jika status reject

                }

                // Update database
                DB::table('t_approval_report')
                    ->where('id_capex', $idCapex)
                    ->update($updateData);

                return response()->json([
                    'success' => 'Digital signature has been added successfully'
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'error' => 'Failed to process document: ' . $e->getMessage()
                ], 500);
            }
        } else if ($flag === 'check-form') {
            // Validasi input
            $request->validate([
                'id_capex' => 'required|exists:t_approval_report,id_capex',
                'date' => 'required',
                'wbs_type' => 'required|in:WBS-P,WBS-A',
                'engineering' => 'required|in:0,1',
                'maintenance' => 'required|in:0,1',
                'outstanding_inventory' => 'required|in:0,1',
                'material' => 'required|in:0,1',
                'jasa' => 'required|in:0,1',
                'etc' => 'required|in:0,1',
                'warehouse' => 'required|in:0,1',
                'user' => 'required|in:0,1',
                'berita_acara' => 'required|in:0,1',
                'remark' => 'required',
            ], [
                'wbs_type.required' => 'WBS Type harus dipilih',
                'wbs_type.in' => 'WBS Type harus WBS-P atau WBS-A',
                'engineering.in' => 'Nilai Engineering harus berupa checkbox',
                'maintenance.in' => 'Nilai Maintenance harus berupa checkbox',
                'outstanding_inventory.in' => 'Nilai Outstanding Inventory harus berupa checkbox',
                'material.in' => 'Nilai Material harus berupa checkbox',
                'jasa.in' => 'Nilai Jasa harus berupa checkbox',
                'etc.in' => 'Nilai Etc harus berupa checkbox',
                'warehouse.in' => 'Nilai Warehouse harus berupa checkbox',
                'user.in' => 'Nilai User harus berupa checkbox',
                'berita_acara.in' => 'Nilai Berita Acara harus berupa checkbox',
            ]);

            $idCapex = $request->input('id_capex');

            // Cari data pada t_approval_report
            $approve = DB::table('t_approval_report')->where('id_capex', $idCapex)->first();

            if (!$approve) {
                return response()->json(['error' => 'Data capex tidak ditemukan di approval report'], 404);
            }

            // Update database tanpa file PDF
            $updateData = [
                'upload_by' => Auth::user()->name,
                'upload_date' => now(),
                'updated_at' => now(),
                'date' => $request->input('date'),
                'wbs_type' => $request->input('wbs_type'),
                'engineering_production' => $request->boolean('engineering'),
                'maintenance' => $request->boolean('maintenance'),
                'outstanding_inventory' => $request->boolean('outstanding_inventory'),
                'material' => $request->boolean('material'),
                'jasa' => $request->boolean('jasa'),
                'etc' => $request->boolean('etc'),
                'warehouse_received' => $request->boolean('warehouse'),
                'user_received' => $request->boolean('user'),
                'berita_acara' => $request->boolean('berita_acara'),
                'remark' => $request->input('remark'),
            ];

            // Update database
            DB::table('t_approval_report')
                ->where('id_capex', $idCapex)
                ->update($updateData);

            return response()->json([
                'success' => 'Data berhasil diTambahkan'
            ]);
        }
    }


    private function generateFromTemplate($type, $data,)
    {
        $reports = DB::table('t_report_cip')
            ->where('id_capex', $data['id_capex'])
            ->get();

        // Hitung total amount
        $totals = [
            'amount_rp' => $reports->sum('amount_rp'),
            'amount_us' => $reports->sum('amount_us')
        ];

        $existingApproval = DB::table('t_approval_report')
            ->where('id_capex', $data['id_capex'])
            ->first();

        $wbs_capex = DB::table('t_approval_report')
            ->where('id_capex', $data['id_capex'])  // Tambahkan where clause untuk id_capex
            ->first();

        $recost_usd = DB::table('t_report_summary')
            ->where('id_capex', $data['id_capex'])  // Tambahkan where clause untuk id_capex
            ->value('recost_usd'); // Use value() to get specific column

        $userRole = auth::user()->roles->first()->name;
        $userId = auth::id();
        $userName = auth::user()->name;

        // Gabungkan data tambahan dengan data lainnya
        $data['reports'] = $reports;
        $data['totals'] = $totals;
        $data['userRole'] = $userRole;
        $data['userId'] = $userId;
        $data['userName'] = $userName;
        $data['wbs_capex'] = $wbs_capex;
        $data['recost_usd'] = $recost_usd;

        if ($userId == 3 && $userRole === 'admin') {
            $data['approved_by_admin_1'] = $userName;
            $data['approved_at_admin_1'] = now()->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s');
        } else {
            $data['approved_by_admin_1'] = $existingApproval->approved_by_admin_1 ?? '';
            $data['approved_at_admin_1'] = $existingApproval->approved_at_admin_1 ? Carbon::parse($existingApproval->approved_at_admin_1)->format('Y-m-d H:i:s') : '';
        }

        // Set data admin 2
        if ($userId == 4 && $userRole === 'admin') {
            $data['approved_by_admin_2'] = $userName;
            $data['approved_at_admin_2'] = now()->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s');
        } else {
            $data['approved_by_admin_2'] = $existingApproval->approved_by_admin_2 ?? '';
            $data['approved_at_admin_2'] = $existingApproval->approved_at_admin_2 ? Carbon::parse($existingApproval->approved_at_admin_2)->format('Y-m-d H:i:s') : '';
        }

        if ($userRole === 'user') {
            $data['approved_by_user'] = $userName;
            $data['approved_at_user'] = now()->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s');
        } else {
            $data['approved_by_user'] = $existingApproval->approved_by_user ?? '';
            $data['approved_at_user'] = $existingApproval->approved_at_user ? Carbon::parse($existingApproval->approved_at_user)->format('Y-m-d H:i:s') : '';
        }

        if ($userRole === 'engineering') {
            $data['approved_by_engineer'] = $userName;
            $data['approved_at_engineer'] = now()->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s');
        } else {
            $data['approved_by_engineer'] = $existingApproval->approved_by_engineer ?? '';
            $data['approved_at_engineer'] = $existingApproval->approved_at_engineer ? Carbon::parse($existingApproval->approved_at_engineer)->format('Y-m-d H:i:s') : '';
        }

        // Render view ke HTML
        $html = view(
            'approve.form.' .
                ($type === 'closing' ? 'form-closing' : ($type === 'detail' ? 'form-detail' : ($type === 'acceptance' ? 'form-accept' : 'unknown-form'))),
            $data
        )->render();

        // Setup Dompdf
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Gunakan path yang sudah ada
        $signedBasePath = storage_path('app/public/uploads/signatures/');
        $signedSubPath = $signedBasePath .
            ($type === 'detail' ? 'signature-detail/' : ($type === 'closing' ? 'signature-closing/' : ($type === 'acceptance' ? 'signature-acceptance/' : 'unknown-type/')));

        // Pastikan subfolder ada
        if (!file_exists($signedSubPath)) {
            mkdir($signedSubPath, 0777, true);
        }

        // Tentukan nama file
        $fileName = $type . '_' . Carbon::now('Asia/Jakarta')->format('d M Y') . '_' . $data['requester'] . '.pdf';
        $filePath = $signedSubPath . $fileName;

        // Cek apakah file sudah ada
        if (file_exists($filePath)) {
            // Jika file ada, perbarui kontennya
            file_put_contents($filePath, $dompdf->output());
        } else {
            // Jika file belum ada, buat file baru
            file_put_contents($filePath, $dompdf->output());
        }

        return $fileName;
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {

        $flag = $request->input('flag');
        if ($flag === 'show-form-detail') {
            $approve = Approve::where('id_capex', $id)->firstOrFail();

            $filename = $approve->signature_detail_file;
            $path = storage_path('app/public/uploads/signatures/signature-detail/' . $filename);

            if (!file_exists($path)) {
                abort(404, 'File not found.');
            }

            return response()->file($path);
        } else if ($flag === 'show-form-closing') {
            $approve = Approve::where('id_capex', $id)->firstOrFail();

            $filename = $approve->signature_closing_file;
            $path = storage_path('app/public/uploads/signatures/signature-closing/' . $filename);

            if (!file_exists($path)) {
                abort(404, 'File not found.');
            }

            return response()->file($path);
        } else if ($flag === 'show-form-acceptance') {
            $approve = Approve::where('id_capex', $id)->firstOrFail();

            $filename = $approve->signature_acceptance;
            $path = storage_path('app/public/uploads/signatures/signature-acceptance/' . $filename);

            if (!file_exists($path)) {
                abort(404, 'File not found.');
            }

            return response()->file($path);
        } else if ($flag === 'show-pdf') {
            $approve = Approve::where('id_capex', $id)->firstOrFail();

            $filename = $approve->file_pdf;
            $path = storage_path('app/public/uploads/approvalFiles/' . $filename);

            if (!file_exists($path)) {
                abort(404, 'File not found.');
            }

            return response()->file($path);
        } else if ($flag === 'show-progress') {
        } else if ($flag === 'checkWBS'){
            $data = DB::table('t_approval_report')->where('id_capex', $id)->first();

            // Periksa apakah wbs_type ada nilainya
            $hasWbsType = !empty($data->wbs_type);

            return response()->json([
                'hasWbsType' => $hasWbsType,
            ]);
        }

        // Kembalikan response jika flag tidak sesuai
        return response()->json(['error' => 'Invalid flag'], 400);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */




    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
