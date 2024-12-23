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

                // Simpan file original
                $PDFfile->storeAs('uploads/approvalFiles', $PDFfileName, 'public');

                // Update database
                DB::table('t_approval_report')
                    ->where('id_capex', $idCapex)
                    ->update([
                        'file_pdf' => $PDFfileName,
                        'upload_by' => Auth::user()->name,
                        'upload_date' => now(),
                        'updated_at' => now(),
                    ]);

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
                return response()->json(['error' => 'Data capex tidak ditemukan di approval report'], 404);
            }

            if ($userRole === 'admin' && $userId == 4 && $approve->status_approve_1 != 1) {
                return response()->json(['error' => 'Menunggu approval dari admin 1'], 403);
            }

            if ($userRole === 'user' && ($approve->status_approve_1 != 1 || $approve->status_approve_4 != 1)) {
                return response()->json(['error' => 'Menunggu approval dari admin 2'], 403);
            }
            if ($userRole === 'engineer' && ($approve->status_approve_1 != 1 || $approve->status_approve_2 != 1 || $approve->status_approve_4 != 1)) {
                return response()->json(['error' => 'Menunggu approval sebelumnya'], 403);
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

                            $pdf->SetFont('Times', '', 10); // Atur font untuk teks

                            // Contoh menambahkan tanda tangan sesuai role
                            if ($userId == 3 && (!$approve->approved_by_admin_1 || $approve->status_approve_1 != 1)) {
                                $pdf->SetXY($positions['admin_1'], 230);
                                $pdf->Cell(40, 5, 'Signed by ' . auth::user()->name, 0, 1);
                                $pdf->SetXY($positions['admin_1'], 235);
                                $pdf->Cell(40, 5, 'Date: ' . now()->setTimezone('Asia/Jakarta')->format('Y-m-d'), 0, 1);
                                $pdf->SetXY($positions['admin_1'], 240);
                                $pdf->Cell(40, 5, now()->setTimezone('Asia/Jakarta')->format('H:i:s'), 0, 1);
                            }
                            // Admin 2
                            else if ($userId == 4 && (!$approve->approved_by_admin_2 || $approve->status_approve_4 != 1)) {
                                $pdf->SetXY($positions['admin_2'], 230);
                                $pdf->Cell(40, 5, 'Signed by ' . auth::user()->name, 0, 1);
                                $pdf->SetXY($positions['admin_2'], 235);
                                $pdf->Cell(40, 5, 'Date: ' . now()->setTimezone('Asia/Jakarta')->format('Y-m-d'), 0, 1);
                                $pdf->SetXY($positions['admin_2'], 240);
                                $pdf->Cell(40, 5, now()->setTimezone('Asia/Jakarta')->format('H:i:s'), 0, 1);
                            }
                            // User
                            else if ($userRole === 'user' && (!$approve->approved_by_user || $approve->status_approve_2 != 1)) {
                                $pdf->SetXY($positions['user'], 230);
                                $pdf->Cell(40, 5, 'Signed by ' . auth::user()->name, 0, 1);
                                $pdf->SetXY($positions['user'], 235);
                                $pdf->Cell(40, 5, 'Date: ' . now()->setTimezone('Asia/Jakarta')->format('Y-m-d'), 0, 1);
                                $pdf->SetXY($positions['user'], 240);
                                $pdf->Cell(40, 5, now()->setTimezone('Asia/Jakarta')->format('H:i:s'), 0, 1);
                            }
                            // Engineer
                            else if ($userRole === 'engineer' && (!$approve->approved_by_engineer || $approve->status_approve_3 != 1)) {
                                $pdf->SetXY($positions['engineer'], 230);
                                $pdf->Cell(40, 5, 'Signed by ' . auth::user()->name, 0, 1);
                                $pdf->SetXY($positions['engineer'], 235);
                                $pdf->Cell(40, 5, 'Date: ' . now()->setTimezone('Asia/Jakarta')->format('Y-m-d'), 0, 1);
                                $pdf->SetXY($positions['engineer'], 240);
                                $pdf->Cell(40, 5, now()->setTimezone('Asia/Jakarta')->format('H:i:s'), 0, 1);
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
    public function show(string $id)
    {
        $approve = Approve::where('id_capex', $id)->firstOrFail();

        $filename = $approve->signature_file;
        $path = storage_path('app/public/uploads/signatures/' . $filename);

        if (!file_exists($path)) {
            abort(404, 'File not found.');
        }

        return response()->file($path);
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
