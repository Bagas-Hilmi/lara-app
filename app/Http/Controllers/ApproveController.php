<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Approve;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Capex;
use Codedge\Fpdf\Fpdf\Fpdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use setasign\Fpdi\Fpdi;
use Illuminate\Support\Facades\Mail;
use App\Mail\ApprovalNotification;
use App\Models\User;

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
            // Gunakan query builder untuk mengambil data
            $query = Approve::getData();  // Ganti dengan query yang sesuai

            return DataTables::of($query)
                ->addColumn('action', function ($row) {
                    return view('approve/datatables/actionbtn', [
                        'row' => $row,
                    ]);
                })

                ->rawColumns(['action'])
                ->make(true);
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
                if ($capexData->wbs_capex === 'Project') {
                    // Menggunakan Laratrust untuk mencari user dengan role 'engineer'
                    $nextUser = User::whereHas('roles', function ($query) {
                        $query->where('name', 'engineer');
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
                'id_capex' => 'required|exists:t_master_capex,id_capex',
                'file_pdf' => 'required|file|mimes:pdf|max:2048',
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
            } else if ($userRole === 'engineer') {
                $statusField = 'status_approve_3';
            } else if ($userRole === 'admin' && $userId === 4) {
                $statusField = 'status_approve_4';
            }


            $request->validate([
                'id_capex' => 'required|exists:t_master_capex,id_capex',
                $statusField => 'required|in:1,2',
            ]);

            $idCapex = $request->input('id_capex');
            $statusApprove = $request->input($statusField);

            $approve = DB::table('t_approval_report')->where('id_capex', $idCapex)->first();

            if (!$approve) {
                abort(404, 'Data capex tidak ditemukan di approval report');
            }
            
            if ($userRole === 'admin' && $userId == 4 && $approve->status_approve_1 != 1) {
                abort(403, 'Menunggu approval dari admin 1');
            }
            
            if ($userRole === 'user' && ($approve->status_approve_1 != 1 || $approve->status_approve_4 != 1)) {
                abort(403, 'Menunggu approval dari admin 2');
            }
            
            if ($userRole === 'engineer' && ($approve->status_approve_1 != 1 || $approve->status_approve_2 != 1 || $approve->status_approve_4 != 1)) {
                abort(403, 'Menunggu approval sebelumnya');
            }
            

            try {
                $updateData = [
                    'updated_at' => now(),
                ];

                // Set data update sesuai role
                if ($userRole === 'admin') {
                    if ($userId == 3) {
                        $updateData['approved_by_admin_1'] = auth::user()->name;
                        $updateData['approved_at_admin_1'] = now();
                        $updateData['status_approve_1'] = $statusApprove;
                    } else if ($userId == 4) {
                        $updateData['approved_by_admin_2'] = auth::user()->name;
                        $updateData['approved_at_admin_2'] = now();
                        $updateData['status_approve_4'] = $statusApprove;
                    }

                    // Reset approval selanjutnya jika disapprove
                    if ($statusApprove == 2) {
                        $updateData['status_approve_2'] = 0;
                        $updateData['status_approve_3'] = 0;
                        $updateData['approved_by_user'] = null;
                        $updateData['approved_by_engineer'] = null;
                    }
                } else if ($userRole === 'user') {
                    $updateData['approved_by_user'] = auth::user()->name;
                    $updateData['approved_at_user'] = now();
                    $updateData['status_approve_2'] = $statusApprove;

                    if ($statusApprove == 2) {
                        $updateData['status_approve_3'] = 0;
                        $updateData['approved_by_engineer'] = null;
                    }
                } else if ($userRole === 'engineer') {
                    $updateData['approved_by_engineer'] = auth::user()->name;
                    $updateData['approved_at_engineer'] = now();
                    $updateData['status_approve_3'] = $statusApprove;
                }

                // Jika approve, proses PDF
                if ($statusApprove == 1) {
                    // Tentukan path file yang akan digunakan
                    $baseFilePath = storage_path('app/public/uploads/approvalFiles/' . $approve->file_pdf);
                    $signatureFilePath = storage_path('app/public/uploads/signatures/' . ($approve->signature_file ?? 'signed_' . $approve->file_pdf));

                    // Gunakan file yang sudah ditandatangani jika ada, jika tidak gunakan file asli
                    $sourcePath = file_exists($signatureFilePath) ? $signatureFilePath : $baseFilePath;

                    if (!file_exists($sourcePath)) {
                        return response()->json(['error' => 'File PDF tidak ditemukan'], 404);
                    }

                    $pdf = new Fpdi();
                    $pageCount = $pdf->setSourceFile($sourcePath);

                    // Salin semua halaman dari file yang ada
                    // Salin semua halaman dari file yang ada
                    for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                        $templateId = $pdf->importPage($pageNo);
                        $pdf->addPage();
                        $pdf->useTemplate($templateId);

                        // Tambahkan tanda tangan hanya di halaman terakhir
                        if ($pageNo == $pageCount) {
                            // Posisi X untuk setiap tanda tangan (berjejer)
                            $positions = [
                                'admin_1' => 20,  // Admin pertama di kiri
                                'admin_2' => 60,  // Admin kedua di tengah
                                'user' => 100,    // User di kanan
                                'engineer' => 140 // Engineer di ujung kanan
                            ];

                            $pdf->SetFont('Arial', '', 8); // Atur font untuk teks

                            // Contoh menambahkan tanda tangan sesuai role
                            if ($userId == 3 && (!$approve->approved_by_admin_1 || $approve->status_approve_1 != 1)) {
                                $pdf->SetFont('Arial', '', 10);
                                $pdf->SetXY($positions['admin_1'], 220);
                                $pdf->Cell(60, 5, 'Prepared by,', 0, 1);

                                $pdf->SetFont('Arial', '', 8);
                                $pdf->SetXY($positions['admin_1'], 230);
                                $pdf->Cell(60, 5, 'Digitally signed', 0, 1);
                                $pdf->SetXY($positions['admin_1'], 235);
                                $pdf->Cell(60, 5, 'by ' . auth::user()->name, 0, 1);
                                $pdf->SetXY($positions['admin_1'], 240);
                                $pdf->Cell(60, 5, 'Date: ' . now()->setTimezone('Asia/Jakarta')->format('Y.m.d'), 0, 1);
                                $pdf->SetXY($positions['admin_1'], 245);
                                $pdf->Cell(40, 5, now()->setTimezone('Asia/Jakarta')->format('H:i:s'), 0, 1);

                                // Nama di bawah
                                $pdf->SetFont('Arial', 'B', 11);
                                $pdf->SetXY($positions['admin_1'], 255);
                                $pdf->Cell(40, 5, auth::user()->name, 0, 1);
                            }
                            // Admin 2
                            else if ($userId == 4 && (!$approve->approved_by_admin_2 || $approve->status_approve_4 != 1)) {
                                // Set font untuk "Prepared by"
                                $pdf->SetFont('Arial', '', 10);
                                $pdf->SetXY($positions['admin_2'], 220);
                                $pdf->Cell(60, 5, 'Reviewed by,', 0, 1);

                                $pdf->SetFont('Arial', '', 8);
                                $pdf->SetXY($positions['admin_2'], 230);
                                $pdf->Cell(60, 5, 'Digitally signed', 0, 1);
                                $pdf->SetXY($positions['admin_2'], 235);
                                $pdf->Cell(60, 5, 'by ' . auth::user()->name, 0, 1);
                                $pdf->SetXY($positions['admin_2'], 240);
                                $pdf->Cell(60, 5, 'Date: ' . now()->setTimezone('Asia/Jakarta')->format('Y.m.d'), 0, 1);
                                $pdf->SetXY($positions['admin_2'], 245);
                                $pdf->Cell(40, 5, now()->setTimezone('Asia/Jakarta')->format('H:i:s'), 0, 1);

                                // Nama di bawah
                                $pdf->SetFont('Arial', 'B', 11);
                                $pdf->SetXY($positions['admin_2'], 255);
                                $pdf->Cell(40, 5, auth::user()->name, 0, 1);
                            }
                            // User
                            else if ($userRole === 'user' && (!$approve->approved_by_user || $approve->status_approve_2 != 1)) {
                                $pdf->SetFont('Arial', '', 10);
                                $pdf->SetXY($positions['user'], 220);
                                $pdf->Cell(60, 5, 'Approved by,', 0, 1);

                                $pdf->SetFont('Arial', '', 8);
                                $pdf->SetXY($positions['user'], 230);
                                $pdf->Cell(60, 5, 'Digitally signed', 0, 1);
                                $pdf->SetXY($positions['user'], 235);
                                $pdf->Cell(60, 5, 'by ' . auth::user()->name, 0, 1);
                                $pdf->SetXY($positions['user'], 240);
                                $pdf->Cell(60, 5, 'Date: ' . now()->setTimezone('Asia/Jakarta')->format('Y.m.d'), 0, 1);
                                $pdf->SetXY($positions['user'], 245);
                                $pdf->Cell(40, 5, now()->setTimezone('Asia/Jakarta')->format('H:i:s'), 0, 1);

                                // Nama di bawah
                                $pdf->SetFont('Arial', 'B', 11);
                                $pdf->SetXY($positions['user'], 255);
                                $pdf->Cell(40, 5, auth::user()->name, 0, 1);
                            }
                            // Engineer
                            else if ($userRole === 'engineer' && (!$approve->approved_by_engineer || $approve->status_approve_3 != 1)) {
                                $pdf->SetFont('Arial', '', 10);
                                $pdf->SetXY($positions['engineer'], 220);
                                $pdf->Cell(60, 5, 'Approved by,', 0, 1);

                                $pdf->SetFont('Arial', '', 8);
                                $pdf->SetXY($positions['engineer'], 230);
                                $pdf->Cell(60, 5, 'Digitally signed', 0, 1);
                                $pdf->SetXY($positions['engineer'], 235);
                                $pdf->Cell(60, 5, 'by ' . auth::user()->name, 0, 1);
                                $pdf->SetXY($positions['engineer'], 240);
                                $pdf->Cell(60, 5, 'Date: ' . now()->setTimezone('Asia/Jakarta')->format('Y.m.d'), 0, 1);
                                $pdf->SetXY($positions['engineer'], 245);
                                $pdf->Cell(40, 5, now()->setTimezone('Asia/Jakarta')->format('H:i:s'), 0, 1);

                                // Nama di bawah
                                $pdf->SetFont('Arial', 'B', 11);
                                $pdf->SetXY($positions['engineer'], 255);
                                $pdf->Cell(40, 5, auth::user()->name, 0, 1);
                            }
                        }
                    }


                    // Simpan PDF dengan tanda tangan
                    $signatureFileName = 'signed_' . $approve->file_pdf;
                    $signatureFilePath = storage_path('app/public/uploads/signatures/' . $signatureFileName);

                    $pdf->Output('F', $signatureFilePath);
                    $updateData['signature_file'] = $signatureFileName;
                }

                DB::table('t_approval_report')
                    ->where('id_capex', $idCapex)
                    ->update($updateData);

                $capexData = DB::table('t_approval_report')
                    ->where('id_capex', $idCapex)
                    ->first();
                $this->sendEmailToNextApprover($capexData, $userRole, $statusApprove);
                return response()->json([
                    'success' => ($statusApprove == 1 ? 'Digital signature has been added successfully' : 'Document has been disapproved successfully')
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'error' => 'Failed to process document: ' . $e->getMessage()
                ], 500);
            }
        }
    }
    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {

        $flag = $request->input('flag');
        if ($flag === 'show-pdf') {
            $approve = Approve::where('id_capex', $id)->firstOrFail();

            $filename = $approve->signature_file;
            $path = storage_path('app/public/uploads/signatures/' . $filename);

            if (!file_exists($path)) {
                abort(404, 'File not found.');
            }

            return response()->file($path);
        } else if ($flag ==='show-form') {
            $project = Approve::where('id_capex', $id)->first(); // Gunakan where untuk mencari id_capex

            // Cek apakah project ditemukan
            if (!$project) {
                return response()->json(['error' => 'Project not found'], 404);
            }
    
            // Kembalikan data project sebagai respons JSON
            return response()->json($project);
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
