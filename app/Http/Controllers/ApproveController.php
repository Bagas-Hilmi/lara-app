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
                    return view('approve/datatables/actionbtn', ['row' => $row]);
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
            $request->validate([
                'id_capex' => 'required|exists:t_master_capex,id_capex',
            ]);

            $idCapex = $request->input('id_capex');

            // Cari data pada t_approval_report
            $approve = DB::table('t_approval_report')->where('id_capex', $idCapex)->first();

            if (!$approve) {
                return response()->json(['error' => 'Data capex tidak ditemukan di approval report'], 404);
            }

            try {
                // Path file PDF yang sudah ada
                $pdfPath = storage_path('app/public/uploads/approvalFiles/' . $approve->file_pdf);  // Use dynamic file name

                // Cek apakah file PDF ada
                if (!file_exists($pdfPath)) {
                    return response()->json(['error' => 'File PDF tidak ditemukan'], 404);
                }

                // Buat instance FPDI (yang sudah mengimport FPDF)
                $pdf = new Fpdi();

                // Tentukan file yang akan dimodifikasi (file PDF yang sudah ada)
                $pageCount = $pdf->setSourceFile($pdfPath);  // Memuat PDF yang sudah ada

                // Pilih halaman pertama (bisa disesuaikan jika lebih dari satu halaman)
                $templateId = $pdf->importPage(1);
                $pdf->addPage();

                // Gunakan halaman yang diimpor
                $pdf->useTemplate($templateId);

                // Set font untuk tanda tangan
                $pdf->SetFont('Times', '', 9);

                // Tentukan posisi tanda tangan
                $pdf->SetXY(20, 250);  // Sesuaikan dengan posisi yang diinginkan dalam PDF

                // Tambahkan teks tanda tangan
                $pdf->Cell(0, 5, 'Approved by: ' . Auth::user()->name, 0, 1); // Baris pertama

                // Pindahkan posisi vertikal ke bawah secara manual
                $pdf->SetX(20); // Tetap di posisi horizontal yang sama
                $pdf->Cell(0, 5, 'Date: ' . now()->format('Y-m-d H:i:s'), 0, 1); // Baris kedua

                // Generate nama file tanda tangan
                $signatureFileName = 'signed_' . $approve->file_pdf . '.pdf';
                $signatureFilePath = storage_path('app/public/uploads/signatures/' . $signatureFileName);

                // Simpan file PDF yang sudah dimodifikasi
                $pdf->Output('F', $signatureFilePath);

                // Update database dengan nama file yang baru
                DB::table('t_approval_report')
                    ->where('id_capex', $idCapex)
                    ->update([
                        'signature_file' => $signatureFileName,
                        'approved_by' => Auth::user()->name,
                        'approved_at' => now(),
                        'updated_at' => now(),
                    ]);

                return response()->json([
                    'success' => 'Digital signature has been added successfully'
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'error' => 'Failed to add signature: ' . $e->getMessage()
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
