<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Capex;
use App\Models\CapexBudget;
use App\Models\CapexProgress;
use Illuminate\Support\Facades\DB;

class CapexController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $status = $request->get('status', 1);

            // Buat query untuk mengambil semua data dari model Capex
            $query = Capex::query()->where('status', $status);

            return DataTables::of($query)
                ->addColumn('action', function ($row) {
                    return view('capex/datatables/actionbtn', ['row' => $row]);
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('capex.index');
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

        // Mengambil nilai flag
        $flag = $request->input('flag');
        // Menjalankan logika berdasarkan nilai flag
        if ($flag === 'add') {
            $validated = $request->validate([
                'flag' => 'required|in:add,update',
                'id_capex'       => 'required_if:flag,update|exists:t_master_capex,id_capex',
                'project_desc'   => 'required|string|max:255',
                'wbs_capex'      => 'required|string',
                'remark'         => 'required|string|max:255',
                'request_number' => 'required|string|max:255',
                'requester'      => 'required|string|max:255',
                'capex_number'   => 'required|string|max:255',
                'amount_budget'  => 'required|numeric',
                'status_capex'   => 'required|string',
                'budget_type'    => 'required|string',
                'startup'        => 'required|string',
                'expected_completed'    => 'required|string',
            ]);
            // Simpan data baru ke database
            $capex = new Capex();
            $capex->project_desc = $validated['project_desc'];
            $capex->wbs_capex = $validated['wbs_capex'];
            $capex->remark = $validated['remark'];
            $capex->request_number = $validated['request_number'];
            $capex->requester = $validated['requester'];
            $capex->capex_number = $validated['capex_number'];
            $capex->amount_budget = $validated['amount_budget'];
            $capex->status_capex = $validated['status_capex'];
            $capex->budget_type = $validated['budget_type'];
            $capex->startup = $validated['startup'];
            $capex->expected_completed = $validated['expected_completed'];
            $capex->save();

            // Kembalikan response sukses
            return response()->json(['success' => 'Data capex berhasil disimpan']);
        } else if ($flag === 'update') {
            $validated = $request->validate([
                'flag' => 'required|in:add,update',
                'id_capex'       => 'required_if:flag,update|exists:t_master_capex,id_capex',
                'project_desc'   => 'required|string|max:255',
                'wbs_capex'      => 'required|string',
                'remark'         => 'required|string|max:255',
                'request_number' => 'required|string|max:255',
                'requester'      => 'required|string|max:255',
                'capex_number'   => 'required|string|max:255',
                'amount_budget'  => 'required|numeric',
                'status_capex'   => 'required|string',
                'budget_type'    => 'required|string',
                'startup'        => 'required|string',
                'expected_completed'    => 'required|string',
            ]);
            // Perbarui data yang sudah ada
            $capex = Capex::find($validated['id_capex']); // Cari data berdasarkan id_capex
            if ($capex) {
                $capex->project_desc = $validated['project_desc'];
                $capex->wbs_capex = $validated['wbs_capex'];
                $capex->remark = $validated['remark'];
                $capex->request_number = $validated['request_number'];
                $capex->requester = $validated['requester'];
                $capex->capex_number = $validated['capex_number'];
                $capex->amount_budget = $validated['amount_budget'];
                $capex->status_capex = $validated['status_capex'];
                $capex->budget_type = $validated['budget_type'];
                $capex->startup = $validated['startup'];
                $capex->expected_completed = $validated['expected_completed'];
                $capex->save();

                // Kembalikan response sukses
                return response()->json(['success' => 'Data capex berhasil diperbarui']);
            } else {
                return response()->json(['error' => 'Data capex tidak ditemukan'], 404);
            }
        } else if ($flag === 'add-budget') {

            $request->validate([
                'flag' => 'required|in:add-budget',
                'description' => 'required|string|max:255',
                'budget_cos' => 'required|numeric|min:0',
            ]);

            // Buat entri baru di t_capex_budget
            $budget = new CapexBudget();
            $budget->description = $request->input('description');
            $budget->budget_cos = $request->input('budget_cos');
            $budget->save();

            // Respons sukses
            return response()->json([
                'success' => true,
                'message' => 'Budget successfully added.',
                'data' => $budget,
            ]);
        }

        // Kembalikan response jika flag tidak valid
        return response()->json(['error' => 'Invalid flag specified.'], 400);
    }



    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        // Mengambil nilai flag dari permintaan
        $flag = $request->input('flag');

        // Jika flag adalah 'budget'
        if ($flag === 'budget') {
            
            // Mengambil data dari tabel t_capex_budget berdasarkan ID Capex
            $query = CapexBudget::where('id_capex', $id);

            // Membuat DataTable dengan kolom aksi
            return DataTables::of($query)
                ->addColumn('action', function ($row) {
                    return view('capex/datatables/actionbtnbudget', ['row' => $row]); // Ganti sesuai dengan view aksi Anda
                })
                ->rawColumns(['action']) // Membuat kolom aksi dapat berisi HTML
                ->make(true);

            // Jika flag adalah 'progress'
        } else if ($flag === 'progress') {
            // Mengambil data dari tabel lain sesuai kebutuhan, misalnya t_capex_progress
            $query = CapexProgress::where('id_capex', $id); // Pastikan untuk menggunakan model yang sesuai

            // Membuat DataTable dengan kolom aksi untuk progress
            return DataTables::of($query)
                ->addColumn('action', function ($row) {
                    return view('capex/datatables/actionbtnprogress', ['row' => $row]); // Ganti sesuai dengan view aksi untuk progress
                })
                ->rawColumns(['action']) // Membuat kolom aksi dapat berisi HTML
                ->make(true);
        }

        // Jika flag tidak valid, Anda dapat mengembalikan respons error atau data default
        return response()->json(['error' => 'Flag tidak valid'], 400);
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
        $capex = Capex::findOrFail($id);
        $capex->status = 0; // Mengubah status menjadi 0
        $capex->save(); // Menyimpan perubahan ke database

        return response()->json(['success' => true]); // Mengembalikan respons JSON
    }
}
