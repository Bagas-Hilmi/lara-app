<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CCB;
use Yajra\DataTables\Facades\DataTables;

class CipCumBalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = CCB::query()->where('status', 1);
    
            return DataTables::of($query)
                ->addColumn('action', function ($row) {
                    $updateBtn = '<button class="btn btn-secondary btn-sm update-btn" data-id="' . $row->id_ccb . '" data-year-month="' . $row->year_month . '" data-balance-usd="' . $row->balance_usd . '" data-balance-rp="' . $row->balance_rp . '" data-cumulative-balance-usd="' . $row->cumulative_balance_usd . '" data-cumulative-balance-rp="' . $row->cumulative_balance_rp . '">Update</button>';
                    $deleteBtn = '<button class="btn btn-danger btn-sm delete-btn" data-id="' . $row->id_ccb . '">Delete</button>';
                    return $updateBtn . ' ' . $deleteBtn;
                })
                ->editColumn('report_status', function ($row) {
                    return $row->report_status == 0 ? 'Belum DiRilis' : 'Sudah DiRilis';
                })
                ->rawColumns(['action'])
                ->make(true);
    }

        return view('cipcumbal.index'); // Pastikan path ini sesuai dengan file blade Anda
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
        $cipCumBal = CCB::findOrFail($id);
        $cipCumBal->status = 0;
        $cipCumBal->save();
        
        return response()->json(['success' => true]);
    }
}
