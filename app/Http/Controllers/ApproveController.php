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
                    // Membuat URL untuk file PDF
                    $pdfUrl = Storage::url('uploads/approvalFiles/' . $row->file_pdf);

                    // Return view action button dengan data-view yang berisi URL PDF
                    return view('approve/datatables/actionbtn', [
                        'row' => $row,
                        'pdfUrl' => $pdfUrl
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
                        'approved_by' => Auth::user()->name,
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

            // Validasi sesuai role
            $statusField = 'status_approve_1';
            if ($userRole === 'user') {
                $statusField = 'status_approve_2';
            } else if ($userRole === 'engineer') {
                $statusField = 'status_approve_3';
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

            // Validasi alur approval
            if ($userRole === 'user' && $approve->status_approve_1 != 1) {
                return response()->json(['error' => 'Menunggu approval dari admin'], 403);
            }
            if ($userRole === 'engineer' && ($approve->status_approve_1 != 1 || $approve->status_approve_2 != 1)) {
                return response()->json(['error' => 'Menunggu approval sebelumnya'], 403);
            }

            try {
                $updateData = [
                    'updated_at' => now(),
                ];

                // Set data update sesuai role
                if ($userRole === 'admin') {
                    $updateData['approved_by'] = auth::user()->name;
                    $updateData['approved_at'] = now();
                    $updateData['status_approve_1'] = $statusApprove;

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
                    $pdfPath = storage_path('app/public/uploads/approvalFiles/' . $approve->file_pdf);

                    if (!file_exists($pdfPath)) {
                        return response()->json(['error' => 'File PDF tidak ditemukan'], 404);
                    }

                    $pdf = new Fpdi();
                    $pageCount = $pdf->setSourceFile($pdfPath);

                    for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                        $templateId = $pdf->importPage($pageNo);
                        $pdf->addPage();
                        $pdf->useTemplate($templateId);
                    }

                    // Set posisi tanda tangan sesuai role
                    $yPosition = 250; // Admin
                    if ($userRole === 'user') {
                        $yPosition = 270;
                    } else if ($userRole === 'engineer') {
                        $yPosition = 290;
                    }

                    $pdf->SetFont('Times', '', 9);
                    $pdf->SetXY(20, $yPosition);
                    $pdf->Cell(0, 5, 'Approved by: ' . auth::user()->name . ' (' . ucfirst($userRole) . ')', 0, 1);
                    $pdf->SetX(20);
                    $pdf->Cell(0, 5, 'Date: ' . now()->format('Y-m-d H:i:s'), 0, 1);

                    $signatureFileName = ($approve->signature_file) ? $approve->signature_file : 'signed_' . $approve->file_pdf . '.pdf';
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

        $filename = $approve->file_pdf;
        $path = storage_path('app/public/uploads/approvalFiles/' . $filename);

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
