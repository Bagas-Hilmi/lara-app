<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Report;
use App\Models\ReportCategory;
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
        $flag = $request->input('flag', 'cip'); // Default flag adalah 'cip'

        if ($flag === 'cip') {
            $descriptions = Report::getActiveCapexDescriptions();
            Report::insertReportCip();
            $engineers = Report::getEngineersForProjects();

            if ($request->ajax()) {
                $query = Report::query()
                    ->join('t_master_capex', 't_report_cip.id_capex', '=', 't_master_capex.id_capex')
                    ->where('t_report_cip.status', 1);

                if ($request->has('capex_id') && $request->input('capex_id') != '') {
                    $query->where('t_report_cip.id_capex', $request->input('capex_id'));
                } elseif ($request->has('status_capex') && $request->input('status_capex') != '') {
                    $query->where('t_master_capex.status_capex', $request->input('status_capex'));
                }

                return DataTables::of($query)->make(true);
            }

            return view('report.reportCip.index', compact('descriptions', 'engineers'));
        } else if ($flag === 'category') {

            if ($request->ajax()) {
                // Memanggil method dari model untuk mendapatkan data
                $data = ReportCategory::getReportCategoryData();
                
                return DataTables::of($data)
                    ->addIndexColumn() //nomor urut
                    ->make(true);
            }

            return view('report.reportCategory.index');
        }

        return redirect()->route('report.index', ['flag' => 'cip'])
        ->with('success', 'Data berhasil ditambahkan!');
    }

    // Download berdasarkan filter
    public function downloadFilteredPDF(Request $request)
    {
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

        // Cek apakah ini Project atau Non-Project
        $isProject = $capexData && $capexData->wbs_capex === 'Project';

        // Jika Project, ambil data engineer
        $engineer = null;
        if ($isProject) {
            $engineer = Report::getEngineersForProjects();
        }

        // Pilih template berdasarkan tipe
        $template = $isProject ? 'report.reportCip.pdf-filtered-project' : 'report.reportCip.pdf-filtered';

        $pdf = PDF::loadView($template, [
            'reports' => $reports,
            'capexData' => $capexData,
            'totals' => $totals,
            'engineer' => $engineer // Tambahkan data engineer ke view
        ]);

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
    public function show($id)
    {
        // Cek apakah parameter pdf=filtered ada
        if (request()->query('pdf') === 'filtered') {
            return $this->downloadFilteredPDF(request());
        }

        // Jika tidak ada parameter 'pdf=filtered', tampilkan detail report biasa
        $report = Report::findOrFail($id);

        return view('reports.show', compact('report'));
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
