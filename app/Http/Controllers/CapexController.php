<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Capex;
use App\Models\CapexBudget;
use App\Models\CapexProgress;
use App\Models\CapexPOrelease;
use App\Models\CapexCompletion;
use App\Models\CapexStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CapexController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login'); // Arahkan ke halaman login
        }

        $availableYears = Capex::getAvailableYears(); // Memanggil metode untuk mendapatkan tahun yang tersedia

        if ($request->ajax()) {
            $status = $request->get('status', 1);
            // Buat query untuk mengambil semua data dari model Capex
            $query = Capex::query()->where('status', $status);

            // Filter berdasarkan tahun yang dipilih
            if ($request->has('year') && !empty($request->year)) {
                $query->where('created_at', 'LIKE', $request->year . '-%');
            }

            return DataTables::of($query)
                ->addColumn('action', function ($row) {
                    return view('capex/datatables/actionbtn', ['row' => $row]);
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('capex.index')->with('availableYears', $availableYears);
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
                'wbs_number'    => 'required|string',
                'cip_number'    => 'required|string',
            ]);

            $userId = Auth::id();

            // Simpan data baru ke database
            $result = Capex::add(
                $validated['project_desc'],
                $validated['wbs_capex'],
                $validated['remark'],
                $validated['request_number'],
                $validated['requester'],
                $validated['capex_number'],
                $validated['amount_budget'],
                $validated['status_capex'],
                $validated['budget_type'],
                $validated['startup'],
                $validated['expected_completed'],
                $validated['wbs_number'],
                $validated['cip_number'],
                $userId
            );

            // Kembalikan response sukses
            return response()->json($result);
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
                'wbs_number'    => 'required|string',
                'cip_number'    => 'required|string',
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
                $capex->wbs_number = $validated['wbs_number'];
                $capex->cip_number = $validated['cip_number'];
                $capex->save();

                $capexStatus = new CapexStatus();
                $capexStatus->id_capex = $capex->id_capex;
                $capexStatus->status = $validated['status_capex'];
                $capexStatus->save();

                // Kembalikan response sukses
                return response()->json(['success' => 'Data capex berhasil diperbarui']);
            } else {
                return response()->json(['error' => 'Data capex tidak ditemukan'], 404);
            }
        } else if ($flag === 'add-budget') {

            $request->validate([
                'flag' => 'required|in:add-budget',
                'id_capex' => 'required',
                'description' => 'required|string|max:255',
                'budget_cos' => 'required|numeric|min:0',
            ]);

            // Panggil metode di model untuk menyimpan anggaran
            $budget = CapexBudget::addBudget(
                $request->input('id_capex'),
                $request->input('description'),
                $request->input('budget_cos')
            );

            // Respons sukses
            return response()->json([
                'success' => true,
                'message' => 'Budget successfully added.',
                'data' => $budget,
            ]);
        } else if ($flag === 'edit-budget') {
            $request->validate([
                'flag' => 'required|in:edit-budget',
                'id' => 'required|exists:t_capex_budget,id_capex_budget', // Pastikan ID ada di database
                'capex_id' => 'required',
                'description' => 'required|string|max:255',
                'budget_cos' => 'required|numeric|min:0',
            ]);

            // Logika untuk mengedit budget yang ada
            // Jika Anda telah menambahkan metode updateBudget di model:
            $budget = CapexBudget::updateBudget(
                $request->input('id'),
                $request->input('description'),
                $request->input('budget_cos'),
                $request->input('capex_id')
            );

            // Respons sukses
            return response()->json([
                'success' => true,
                'message' => 'Budget berhasil diperbarui!',
                'data' => $budget,
            ]);
        } else if ($flag === 'add-progress') {
            $request->validate([
                'flag' => 'required|in:add-progress',
                'id_capex' => 'required',
                'tanggal' => 'required',
                'description' => 'required|string|max:255',
            ]);

            $progress = CapexProgress::addProgress($request->all());


            // Respons sukses
            return response()->json([
                'success' => true,
                'message' => 'Description successfully added.',
                'data' => $progress,
            ]);
        } else if ($flag === 'edit-progress') {

            $request->validate([
                'flag' => 'required|in:edit-progress',
                'id' => 'required|exists:t_capex_progress,id_capex_progress', // Pastikan ID ada di database
                'id_capex' => 'required',
                'tanggal' => 'required',
                'description' => 'required|string|max:255',
            ]);

            $progress = CapexProgress::editProgress($request->input('id'), $request->all());


            return response()->json([
                'success' => true,
                'message' => 'Progress berhasil diperbarui!',
                'data' => $progress,
            ]);
        } else if ($flag === 'add-porelease') {

            $request->validate([
                'flag' => 'required|in:add-porelease',
                'id_capex' => 'required',
                'description' => 'required|string|max:255',
                'po_release' => 'required|numeric|min:0',
            ]);

            $porelease = CapexPOrelease::addPORelease($request->all());

            CapexPOrelease::get_dtCapexPOrelease();

            // Respons sukses
            return response()->json([
                'success' => true,
                'message' => 'Description successfully added.',
                'data' => $porelease,
            ]);
        } else if ($flag === 'edit-porelease') {

            $request->validate([
                'flag' => 'required|in:edit-porelease',
                'id' => 'required|exists:t_capex_porelease,id_capex_porelease', // Pastikan ID ada di database
                'id_capex' => 'required',
                'po_release' => 'required',
                'description_porelease' => 'required|string|max:255',
            ]);

            $porelease = CapexPOrelease::editPORelease($request->input('id'), $request->all());


            CapexPOrelease::get_dtCapexPOrelease();

            return response()->json([
                'success' => true,
                'message' => 'PO Release berhasil diperbarui!',
                'data' => $porelease,
            ]);
        } else if ($flag === 'add-completion') {

            $request->validate([
                'flag' => 'required|in:add-completion',
                'id_capex' => 'required',
                'date' => 'required|string|max:255',
            ]);

            $completion = CapexCompletion::addCompletion($request->all());

            // Respons sukses
            return response()->json([
                'success' => true,
                'message' => 'Description successfully added.',
                'data' => $completion,
            ]);
        } else if ($flag === 'edit-completion') {

            $request->validate([
                'flag' => 'required|in:edit-completion',
                'id' => 'required|exists:t_capex_completion_date,id_capex_completion', // Pastikan ID ada di database
                'id_capex' => 'required',
                'date' => 'required|string|max:255',
            ]);

            $completion = CapexCompletion::editCompletion($request->input('id'), $request->all());

            return response()->json([
                'success' => true,
                'message' => 'Completion Date berhasil diperbarui!',
                'data' => $completion
            ]);
        }

        return response()->json(['error' => 'Invalid flag specified.'], 400);
    }



    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        // Mengambil nilai flag dari permintaan
        $flag = $request->input('flag');

        if ($flag === 'budget') {
            // Jika permintaan AJAX untuk DataTables
            if ($request->ajax()) {

                // Ambil status dari request, jika tidak ada default ke 1
                $status = $request->get('status', 1);

                // Buat query untuk mengambil data berdasarkan id_capex dan status
                $query = CapexBudget::query()
                    ->where('id_capex', $id) // Ambil data berdasarkan id_capex
                    ->where('status', $status); // Ambil data berdasarkan status

                return DataTables::of($query)
                    ->addColumn('action', function ($row) {
                        return view('capex/datatables/actionbtnbudget', ['row' => $row]);
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }

            // Jika flag adalah 'progress'
        } else if ($flag === 'progress') {
            $status = $request->get('status', 1);

            $query = CapexProgress::query()
                ->where('id_capex', $id)
                ->where('status', $status);

            return DataTables::of($query)
                ->addColumn('action', function ($row) {
                    return view('capex/datatables/actionbtnprogress', ['row' => $row]);
                })
                ->rawColumns(['action'])
                ->make(true);
        } else if ($flag === 'porelease') {

            $status = $request->get('status', 1);


            $query = CapexPOrelease::query()
                ->where('id_capex', $id)
                ->where('status', $status);


            return DataTables::of($query)
                ->addColumn('action', function ($row) {
                    return view('capex/datatables/actionbtnporelease', ['row' => $row]);
                })
                ->rawColumns(['action'])
                ->make(true);
        } else if ($flag === 'completion') {

            $status = $request->get('status', 1);


            $query = CapexCompletion::query()
                ->where('id_capex', $id)
                ->where('status', $status);


            return DataTables::of($query)
                ->addColumn('action', function ($row) {
                    return view('capex/datatables/actionbtncompletion', ['row' => $row]);
                })
                ->rawColumns(['action'])
                ->make(true);
        } else if ($flag === 'status') {

            $query = CapexStatus::query()->where('id_capex', $id);

            return DataTables::of($query)
                ->make(true);
        }

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
    public function destroy(Request $request, string $id)
    {
        $flag = $request->input('flag'); // Ambil nilai flag dari request

        if ($flag === 'capex') {
            // Logika untuk menghapus Capex
            $capex = Capex::findOrFail($id);
            $capex->status = 0;
            $capex->save();

            return response()->json(['success' => true, 'message' => 'Capex berhasil dinonaktifkan!']);
        } elseif ($flag === 'budget') {

            $budget = CapexBudget::findOrFail($id);

            $budgetCosToRemove = $budget->budget_cos;

            $budget->status = 0;
            $budget->save();

            $totalBudgetCos = CapexBudget::where('id_capex', $budget->id_capex)
                ->where('status', 1)
                ->sum('budget_cos');

            $masterCapex = Capex::findOrFail($budget->id_capex);
            $masterCapex->budget_cos = $totalBudgetCos;
            $masterCapex->save();
            return response()->json(['success' => true, 'message' => 'Budget berhasil dinonaktifkan!']);
        } elseif ($flag === 'progress') {
            $progress = CapexProgress::findOrFail($id);
            $progress->status = 0;
            $progress->save();

            return response()->json(['success' => true, 'message' => 'Progress berhasil dinonaktifkan!']);
        } elseif ($flag === 'porelease') {

            $progress = CapexPOrelease::findOrFail($id);
            $progress->status = 0;
            $progress->save();

            return response()->json(['success' => true, 'message' => 'PO Release berhasil dinonaktifkan!']);
        } elseif ($flag === 'completion') {

            // Temukan entri di t_capex_completion
            $completion = CapexCompletion::findOrFail($id);

            // Set status menjadi 0 (nonaktif)
            $completion->status = 0;
            $completion->save();

            // Temukan entri terkait di t_master_capex
            $completion = Capex::findOrFail($completion->id_capex);

            // Update revise_completion_date di t_master_capex
            $completion->revise_completion_date = null; // atau set ke nilai lain sesuai kebutuhan
            $completion->save(); // Simpan perubahan ke tabel t_master_capex


            return response()->json(['success' => true, 'message' => 'Completion Date berhasil dinonaktifkan!']);
        }

        return response()->json(['success' => false, 'message' => 'Invalid flag!'], 400);
    }
}
