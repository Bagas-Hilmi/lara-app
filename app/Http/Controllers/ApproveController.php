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

class ApproveController extends Controller
{
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
        try {
            // Validasi input
            $request->validate([
                'id_capex' => 'required|exists:t_master_capex,id_capex',
                'file_pdf' => 'required|file|mimes:pdf|max:2048',
            ]);

            // Mendapatkan id_capex dari request
            $idCapex = $request->input('id_capex');

            // Cari data pada t_approval_report
            $approve = DB::table('t_approval_report')->where('id_capex', $idCapex)->first();

            if (!$approve) {
                return response()->json(['error' => 'Data capex tidak ditemukan di approval report'], 404);
            }

            // Proses upload file PDF
            if ($request->hasFile('file_pdf')) {
                $PDFfile = $request->file('file_pdf');
                $PDFfileName = $PDFfile->getClientOriginalName();  // Mendapatkan nama file asli
                $PDFfilePath = $PDFfile->storeAs('uploads/approvalFiles', $PDFfileName, 'public');  // Menyimpan file

                // Update data pada t_approval_report dengan nama file
                DB::table('t_approval_report')
                    ->where('id_capex', $idCapex)
                    ->update([
                        'file_pdf' => $PDFfileName,  // Hanya nama file yang disimpan di kolom file_pdf
                        'updated_at' => now(),
                    ]);

                return response()->json([
                    'success' => 'File PDF berhasil diunggah',
                    'file_path' => $PDFfilePath,
                ]);
            }

            return response()->json(['error' => 'File PDF tidak ditemukan'], 400);
        } catch (\Exception $e) {
            Log::error('Upload PDF Error: ' . $e->getMessage());

            return response()->json([
                'error' => 'Terjadi kesalahan saat upload file'
            ], 500);
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
