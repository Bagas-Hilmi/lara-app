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
                ->addColumn('action', function ($row) {
                    return view('cipcumbal/datatables.actionbtn', ['row' => $row]);
                })
                ->editColumn('report_status', function ($row) {
                    return $row->report_status == 0 ? 'Unreleased' : 'Release';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('cipcumbal.index')->with('availableYears', $availableYears) ->with('periodRelease', $periodRelease);
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
        // Validasi input
        $validated = $request->validate([
            'flag' => 'required|in:add,update',
            'id' => 'required_if:flag,update|exists:t_cip_cum_bal,id_ccb',
            'period_cip' => 'required|date_format:Y-m',
            'bal_usd' => 'required|numeric',
            'bal_rp' => 'required|numeric',
            'cumbal_usd' => 'required|numeric',
            'cumbal_rp' => 'required|numeric',
        ]);

        $flag = $request->input('flag'); // Mengambil nilai flag
        $result = match ($flag) {
            'add' => CCB::add(
                $request->input('period_cip'),
                $request->input('bal_usd'),
                $request->input('bal_rp'),
                $request->input('cumbal_usd'),
                $request->input('cumbal_rp'),
                $request->input('report_status', 0),
                $request->input('status', 1),
                Auth::id()
            ),
            'update' => CCB::updateData(
                $request->input('id'),
                $request->input('period_cip'),
                $request->input('bal_usd'),
                $request->input('bal_rp'),
                $request->input('cumbal_usd'),
                $request->input('cumbal_rp'),
                $request->input('report_status', 0),
                $request->input('status', 1),
                Auth::id()
            ),
            default => throw new \Exception('Invalid flag specified.') // Menghadapi nilai yang tidak valid
        };

        return response()->json($result); // Mengembalikan response JSON
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
