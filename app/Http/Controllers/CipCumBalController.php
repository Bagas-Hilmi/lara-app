<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CCB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\DB;

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
        $availableYears = DB::table('t_cip_cum_bal')
            ->selectRaw('DISTINCT LEFT(period_cip, 4) as year')
            ->orderBy('year', 'desc')
            ->pluck('year');

        if ($request->ajax()) {
            $status = $request->get('status', 1);
            $query = CCB::query()->where('status', $status);

            // Filter berdasarkan tahun yang dipilih
            if ($request->has('year') && !empty($request->year)) {
                $query->where('period_cip', 'LIKE', $request->year . '-%');
            }

            return DataTables::of($query)
                ->addColumn('action', function ($row) {
                    $updateBtn = '<button class="btn btn-secondary btn-sm update-btn" 
                            data-id="' . $row->id_ccb . '"
                            data-period="' . $row->period_cip . '"
                            data-bal-usd="' . $row->bal_usd . '"
                            data-bal-rp="' . $row->bal_rp . '"
                            data-cumbal-usd="' . $row->cumbal_usd . '"
                            data-cumbal-rp="' . $row->cumbal_rp . '">Update</button>';
                    $deleteBtn = '<button class="btn btn-danger btn-sm delete-btn" data-id="' . $row->id_ccb . '">Delete</button>';
                    return $updateBtn . ' ' . $deleteBtn;
                })
                ->editColumn('report_status', function ($row) {
                    return $row->report_status == 0 ? 'Belum Di Export' : 'Sudah Di Export';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('cipcumbal.index', compact('availableYears'));
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

        $validated = $request->validate([
            'mode' => 'required|in:ADD,UPDATE',
            'id' => 'required_if:mode,UPDATE|exists:t_cip_cum_bal,id_ccb',
            'period_cip' => 'required|date_format:Y-m',
            'bal_usd' => 'required|numeric',
            'bal_rp' => 'required|numeric',
            'cumbal_usd' => 'required|numeric',
            'cumbal_rp' => 'required|numeric',
        ]);

        $mode = $request->input('mode');
        $result = match ($mode) {
            'ADD' => CCB::add(
                $request->input('period_cip'),
                $request->input('bal_usd'),
                $request->input('bal_rp'),
                $request->input('cumbal_usd'),
                $request->input('cumbal_rp'),
                $request->input('report_status', 0),
                $request->input('status', 1),
                Auth::id(),
                Auth::id()
            ),
            'UPDATE' => CCB::updateData(
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
            default => throw new \Exception('Invalid mode specified.')
        };

        return response()->json($result);
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
