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
use Dompdf\Dompdf;
use Dompdf\Options;

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
            // Cek tipe data yang diminta dari parameter
            if ($request->type === 'progress') {
                // Query untuk data progress approval
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
                    ]);

                return DataTables::of($query)
                    ->addIndexColumn()
                    ->addColumn('actions', function ($row) {
                        return '<button class="btn btn-sm btn-info">View</button>';
                    })
                    ->rawColumns(['actions'])
                    ->make(true);
            } else {
                // Query untuk data approve yang sudah ada sebelumnya
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
                        $updateData['approved_at_admin_1'] = now();
                        $updateData['status_approve_1'] = $statusApprove;
                    } else if ($userId == 4) {
                        $updateData['approved_by_admin_2'] = auth::user()->name;
                        $updateData['approved_at_admin_2'] = now();
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
                    $updateData['approved_at_user'] = now();
                    $updateData['status_approve_2'] = $statusApprove;

                    if ($statusApprove == 2) {
                        $updateData['status_approve_3'] = 0;
                        $updateData['approved_by_engineer'] = null;
                    }
                } else if ($userRole === 'engineering') {
                    $updateData['approved_by_engineer'] = auth::user()->name;
                    $updateData['approved_at_engineer'] = now();
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
                    $signedDetail = $this->addSignature($detailFile, $userRole, $userId, 'detail', $idCapex);

                    // Generate form closing
                    $closingFile = $this->generateFromTemplate('closing', $data);
                    $signedClosing = $this->addSignature($closingFile, $userRole, $userId, 'closing', $idCapex);

                    $acceptanceFile = $this->generateFromTemplate('acceptance', $data);
                    $signedAcceptance = $this->addSignature($acceptanceFile, $userRole, $userId, 'acceptance', $idCapex);

                    // Update data dengan nama file yang sudah ditandatangani
                    $updateData['signature_detail_file'] = $signedDetail;
                    $updateData['signature_closing_file'] = $signedClosing;
                    $updateData['signature_acceptance'] = $signedAcceptance;

                    $this->sendEmailToNextApprover($data, $userRole, $statusApprove);
                } else if ($statusApprove == 2) {  // Jika status reject
                    $updateData['reason'] = $request->input('reason');
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
        }
    }


    private function generateFromTemplate($type, $data)
    {

        $reports = DB::table('t_report_cip')
            ->where('id_capex', $data['id_capex']) // Pastikan ID capex sesuai dengan data yang dikirimkan
            ->get();

        // Hitung total amount
        $totals = [
            'amount_rp' => $reports->sum('amount_rp'),
            'amount_us' => $reports->sum('amount_us')
        ];

        // Gabungkan data tambahan dengan data lainnya
        $data['reports'] = $reports;
        $data['totals'] = $totals;

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

        // Simpan PDF temporary
        $tempPath = storage_path('app/public/temp/');
        if (!file_exists($tempPath)) {
            mkdir($tempPath, 0777, true);
        }

        $tempFile = $tempPath . uniqid() . '.pdf';
        file_put_contents($tempFile, $dompdf->output());

        return $tempFile;
    }

    private function addSignature($sourcePath, $userRole, $userId, $type, $idCapex)
    {
        // Ambil id_capex dari t_approval_report berdasarkan current user
        $approvalReport = DB::table('t_approval_report')
            ->where('id_capex', $idCapex)
            ->first();


        if (!$approvalReport) {
            throw new \Exception('Data approval tidak ditemukan');
        }

        $idCapex = $approvalReport->id_capex;

        // Siapkan direktori penyimpanan
        $signedBasePath = storage_path('app/public/uploads/signatures/');
        $signedSubPath = $signedBasePath . ($type === 'detail' ? 'signature-detail/' : ($type === 'closing' ? 'signature-closing/' : ($type === 'acceptance' ? 'signature-acceptance/' : 'unknown-type/')));

        if (!file_exists($signedSubPath)) {
            mkdir($signedSubPath, 0777, true);
        }

        // Nama file berdasarkan id_capex
        $signedFileName = 'signed_' . $type . '_' . $idCapex . '.pdf';
        $existingFilePath = $signedSubPath . $signedFileName;

        // Gunakan file yang sudah ada atau source file baru
        $sourceFile = $sourcePath;

        $pdf = new Fpdi();
        $pageCount = $pdf->setSourceFile($sourceFile);

        // Posisi tanda tangan
        $positions = [
            'admin_1' => 20,  // Prepared by
            'admin_2' => 60,  // Reviewed by
            'user' => 100,    // Approved by
            'engineer' => 140 // Approved by
        ];

        // Salin semua halaman
        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
            $templateId = $pdf->importPage($pageNo);
            $pdf->addPage();
            $pdf->useTemplate($templateId);

            // Tambah semua tanda tangan di halaman terakhir
            if ($pageNo == $pageCount) {
                $y = 220; // Posisi Y untuk tanda tangan

                // Ambil data tanda tangan yang sudah ada
                $existingSignatures = DB::table('t_approval_report')
                    ->where('id_capex', $idCapex)
                    ->first();

                // Gambar tanda tangan yang sudah ada
                if ($existingSignatures) {
                    if ($existingSignatures->approved_by_admin_1 && $userId != 3) {  // Tambah pengecekan userId
                        $this->drawSignature(
                            $pdf,
                            $positions['admin_1'],
                            $y,
                            'Prepared by',
                            $existingSignatures->approved_by_admin_1,  // Gunakan nama dari database
                            $existingSignatures->approved_at_admin_1   // Gunakan waktu dari database
                        );
                    }

                    if ($existingSignatures->approved_by_admin_2 && $userId != 4) {  // Tambah pengecekan userId
                        $this->drawSignature(
                            $pdf,
                            $positions['admin_2'],
                            $y,
                            'Reviewed by',
                            $existingSignatures->approved_by_admin_2,  // Gunakan nama dari database
                            $existingSignatures->approved_at_admin_2   // Gunakan waktu dari database
                        );
                    }
                    if ($existingSignatures->approved_by_user) {
                        $this->drawSignature(
                            $pdf,
                            $positions['user'],
                            $y,
                            'Approved by',
                            $existingSignatures->approved_by_user,
                            $existingSignatures->approved_at_user
                        );
                    }
                    if ($existingSignatures->approved_by_engineer) {
                        $this->drawSignature(
                            $pdf,
                            $positions['engineer'],
                            $y,
                            'Approved by',
                            $existingSignatures->approved_by_engineer,
                            $existingSignatures->approved_at_engineer
                        );
                    }
                }

                // Tambah tanda tangan baru
                if ($userRole === 'admin') {
                    if ($userId == 3 && !$existingSignatures->approved_by_admin_1) {
                        $this->drawSignature($pdf, $positions['admin_1'], $y, 'Prepared by', Auth::user()->name, now());
                    } else if ($userId == 4 && !$existingSignatures->approved_by_admin_2) {
                        $this->drawSignature($pdf, $positions['admin_2'], $y, 'Reviewed by', Auth::user()->name, now());
                    }
                } else if ($userRole === 'user' && !$existingSignatures->approved_by_user) {
                    $this->drawSignature($pdf, $positions['user'], $y, 'Approved by', Auth::user()->name, now());
                } else if ($userRole === 'engineering' && !$existingSignatures->approved_by_engineer) {
                    $this->drawSignature($pdf, $positions['engineer'], $y, 'Approved by', Auth::user()->name, now());
                }
            }
        }

        // Simpan file
        $pdf->Output('F', $existingFilePath);

        if ($sourcePath !== $existingFilePath) {
            unlink($sourcePath);
        }

        return $signedFileName;
    }

    private function drawSignature($pdf, $x, $y, $title, $name)
    {
        $pdf->SetFont('Arial', '', 10);
        $pdf->SetXY($x, $y);
        $pdf->Cell(60, 5, $title, 0, 1);

        $pdf->SetFont('Arial', '', 8);
        $pdf->SetXY($x, $y + 10);
        $pdf->Cell(60, 5, 'Digitally signed', 0, 1);
        $pdf->SetXY($x, $y + 15);
        $pdf->Cell(60, 5, 'by ' . $name, 0, 1);
        $pdf->SetXY($x, $y + 20);
        $pdf->Cell(60, 5, 'Date: ' . now()->setTimezone('Asia/Jakarta')->format('Y.m.d'), 0, 1);
        $pdf->SetXY($x, $y + 25);
        $pdf->Cell(40, 5, now()->setTimezone('Asia/Jakarta')->format('H:i:s'), 0, 1);

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetXY($x, $y + 35);
        $pdf->Cell(40, 5, $name, 0, 1);
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
