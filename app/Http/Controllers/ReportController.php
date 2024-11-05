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

        Report::insertReportCip(); // Memanggil metode untuk menyisipkan data ke t_report_cip

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
        $query = Report::query();

        if ($request->has('capex_id')) {
            $query->where('id_capex', $request->capex_id);
        }


        $reports = $query->orderBy('date', 'asc')->get(); // Ubah di sini

        // Hitung total
        $totals = [
            'amount_rp' => $reports->sum('amount_rp'),
            'amount_us' => $reports->sum('amount_us')
        ];

        $capexData = Report::getActiveCapexDescriptions()
            ->where('id_capex', $request->capex_id)
            ->first();

        // Menyiapkan data untuk view
        $viewData = [
            'reports' => $reports,
            'capexData' => $capexData,
            'totals' => $totals,
            'signature_name' => $request->input('signature_name', '-'),
            'wbs_capex' => $capexData ? $capexData->wbs_capex : null  // Menambahkan wbs_capex
        ];

        // Hanya tambahkan confirmed_name jika wbs_capex bukan "Non Project"
        if ($capexData && $capexData->wbs_capex !== "Non Project") {
            $viewData['confirmed_name'] = $request->input('confirmed_name', '-');
        } else {
            $viewData['confirmed_name'] = null; // atau bisa juga tidak dimasukkan ke viewData
        }

        $pdf = PDF::loadView('report.pdf-filtered', $viewData);

        $fileName = 'report-capex';
        if ($capexData) {
            $cleanCapexNumber = preg_replace('/[\/\\\]/', '-', $capexData->capex_number);
            $fileName .= '-' . $cleanCapexNumber;
        }
        $fileName .= '_' . date('Y-m-d') . '.pdf';

        return $pdf->download($fileName);
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
