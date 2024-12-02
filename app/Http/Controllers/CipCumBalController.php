<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CCB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CipCumBalController extends Controller
{
    use ValidatesRequests;

    public function __construct()
    {
        $this->middleware('auth'); // Pastikan middleware auth diterapkan
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $availableYears = CCB::getAvailableYears();
        $periodRelease = CCB::getPeriodRelease();

        if ($request->ajax()) {
            $status = $request->get('status', 1);
            $query = CCB::query()->where('status', $status);

            // Filter berdasarkan tahun yang dipilih
            if ($request->has('year') && !empty($request->year)) {
                $query->where('period_cip', 'LIKE', $request->year . '-%');
            }

            return DataTables::of($query)
                ->addIndexColumn() //nomor urut
                ->addColumn('action', function ($row) {
                    return view('cipcumbal/datatables.actionbtn', ['row' => $row]);
                })
                ->addColumn('report_status', function ($row) {
                    return view('cipcumbal/datatables.reportbtn', ['row' => $row]);
                })
                ->rawColumns(['action', 'report_status'])
                ->make(true);
        }

        return view('cipcumbal.index')->with('availableYears', $availableYears)->with('periodRelease', $periodRelease);
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
        $flag = $request->input('flag'); // Ambil nilai flag

        if ($flag == 'add') {
            // Validasi untuk operasi add
            $validated = $request->validate([
                'period_cip' => 'required|date_format:Y-m',
                'bal_usd' => 'required|numeric',
                'bal_rp' => 'required|numeric',
                'cumbal_usd' => 'required|numeric',
                'cumbal_rp' => 'required|numeric',
            ]);

            // Operasi add
            $result = CCB::add(
                $request->input('period_cip'),
                $request->input('bal_usd'),
                $request->input('bal_rp'),
                $request->input('cumbal_usd'),
                $request->input('cumbal_rp'),
                $request->input('report_status', 0),
                $request->input('status', 1),
                Auth::id()
            );

            // Kembalikan respons JSON untuk add
            return response()->json($result);
        } elseif ($flag == 'update') {
            // Validasi untuk operasi update
            $validated = $request->validate([
                'id' => 'required|exists:t_cip_cum_bal,id_ccb',
                'period_cip' => 'required|date_format:Y-m',
                'bal_usd' => 'required|numeric',
                'bal_rp' => 'required|numeric',
                'cumbal_usd' => 'required|numeric',
                'cumbal_rp' => 'required|numeric',
            ]);

            // Operasi update
            $result = CCB::updateData(
                $request->input('id'),
                $request->input('period_cip'),
                $request->input('bal_usd'),
                $request->input('bal_rp'),
                $request->input('cumbal_usd'),
                $request->input('cumbal_rp'),
                $request->input('report_status', 0),
                $request->input('status', 1),
                Auth::id()
            );

            // Kembalikan respons JSON untuk update
            return response()->json($result);
        } elseif ($flag == 'update-btn'){
             $ccbId = $request->input('id_ccb');
             $newStatus = $request->input('status');

             $report = CCB::where('id_ccb', $ccbId)->first();

             if ($report){
                if ($report->report_status ==1){
                    return response()->json([
                        'success'=> false,
                        'message'=> 'Report sudah released'
                    ]);
                }

                $report->report_status = $newStatus;
                $report->save();

                return response()->json([
                    'success'=> true,
                    'message'=> 'Status berhasil diperbarui'
                ]);
             }else {
                return response()->json([
                    'success' => false,
                    'message' => 'Report dengan id_head tidak ditemukan'
                ]);
            }

        }else {
            throw new \Exception('Flag yang diberikan tidak valid.'); // Tangani flag tidak valid
        }
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
        $cipCumBal = CCB::findOrFail($id);
        $cipCumBal->status = 0;
        $cipCumBal->save();

        return response()->json(['success' => true]);
    }
}
