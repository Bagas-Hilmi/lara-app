<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Report;
use App\Models\Capex;
use Yajra\DataTables\Facades\DataTables;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $descriptions = Report::getActiveCapexDescriptions(); // Memanggil metode untuk mendapatkan deskripsi Capex yang aktif

        Report::insertReportCip(); // Memanggil metode untuk menyisipkan data ke t_report_cop

        if ($request->ajax()) {
            // Ambil status dari permintaan, dengan nilai default 1 jika tidak ada
            $status = $request->get('status', 1);

            // Buat query berdasarkan model Report
            $query = Report::query()->where('status', $status);

            // Periksa jika ada capex_id dalam permintaan
            if ($request->has('capex_id') && $request->input('capex_id') != '') {
                $capexId = $request->input('capex_id');
                // Tambahkan filter berdasarkan id_capex
                $query->where('id_capex', $capexId);
            }

            return DataTables::of($query)
                ->make(true);
        }

        return view('report.index', compact('descriptions'));  // Mengirimkan data ke view
    }

    // Download berdasarkan filter
    public function downloadFilteredPDF(Request $request)
    {
        try {
            $query = Report::query();

            if ($request->has('capex_id')) {
                $query->where('id_capex', $request->capex_id);
            }

            $reports = $query->get();

            // Hitung total
            $totals = [
                'amount_rp' => $reports->sum('amount_rp'),
                'amount_us' => $reports->sum('amount_us')
            ];

            $capexData = Report::getActiveCapexDescriptions()
                ->where('id_capex', $request->capex_id)
                ->first();

            $pdf = PDF::loadView('report.pdf-filtered', [
                'reports' => $reports,
                'capexData' => $capexData,
                'totals' => $totals 
            ]);

            $fileName = 'report-capex';
            if ($capexData) {
                $cleanCapexNumber = preg_replace('/[\/\\\]/', '-', $capexData->capex_number);
                $fileName .= '-' . $cleanCapexNumber;
            }
            $fileName .= '_' . date('Y-m-d') . '.pdf';

            return $pdf->download($fileName);
        } catch (\Exception $e) {
            Log::error('PDF Generation Error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Terjadi kesalahan saat membuat PDF'
            ], 500);
        }
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
