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
use App\Models\CapexEngineer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Approve;
use App\Imports\AssetImport;
use App\Models\ListAsset;
use Maatwebsite\Excel\Facades\Excel;

class CapexController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Memanggil metode
        $Years = Capex::getAvailableYears();
        $totalBudget = Capex::getTotalBudget();
        $daysLate = Capex::getDaysLate();
        $daysRemaining = Capex::getDaysRemaining();
        $capexId = $request->get('id_engineer');

        // Jika tidak ada capexId, maka ambil semua data engineer
        if (!$capexId) {
            $engineers = CapexEngineer::all();  // Mengambil semua engineer tanpa filter
        } else {
            $engineers = CapexEngineer::where('id_engineer', $capexId)->get();
        }

        if ($request->ajax()) {
            $query = Capex::query()->whereIn('status', [1, 2]);

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

        return view('capex.index', [
            'Years' => $Years,
            'totalBudget' => $totalBudget,
            'daysLate' => $daysLate,
            'daysRemaining' => $daysRemaining,
            'capexId' => $capexId,  // Pastikan id_capex diteruskan ke view
            'engineers' => $engineers

        ]);
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

        $flag = $request->input('flag');

        if ($flag === 'add') {
            $validated = $request->validate([
                'flag' => 'required|in:add,update',
                'id_capex'       => 'required_if:flag,update|exists:t_master_capex,id_capex',
                'project_desc'   => 'required|string|max:255',
                'wbs_capex'      => 'required|string',
                'category'      => 'required|string',
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
                $validated['category'],
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
                'flag' => 'required|in:update',
                'id_capex' => 'required_if:flag,update|exists:t_master_capex,id_capex',
                'project_desc' => 'required|string|max:255',
                'wbs_capex' => 'required|string',
                'category' => 'required|string',
                'remark' => 'required|string|max:255',
                'request_number' => 'required|string|max:255',
                'requester' => 'required|string|max:255',
                'capex_number' => 'required|string|max:255',
                'amount_budget' => 'required|numeric',
                'status_capex' => 'required|string',
                'budget_type' => 'required|string',
                'startup' => 'required|string',
                'expected_completed' => 'required|string',
                'wbs_number' => 'required|string',
                'cip_number' => 'required|string',
                'file_asset' => 'required_if:status_capex,Close,flag,update|file|mimes:xlsx,xls',
                'capdate' => 'required_if:status_capex,Close,flag,update|string',
                'capdoc' => 'required_if:status_capex,Close,flag,update|string|max:255',
            ]);

            if ($validated['status_capex'] === 'Close') {
                $approval = Approve::where('id_capex', $validated['id_capex'])->first();
        
                if (!$approval) {
                    return response()->json(['error' => 'Data persetujuan tidak ditemukan'], 404);
                }
        
                if ($validated['wbs_capex'] === 'Project') {
                    if ($approval->status_approve_1 != 1 || 
                        $approval->status_approve_2 != 1 || 
                        $approval->status_approve_3 != 1 || 
                        $approval->status_approve_4 != 1) {
                        return response()->json(['error' => 'Cannot close. All approvals must be approved for Project.'], 422);
                    }
                } else if ($validated['wbs_capex'] === 'Non Project') {
                    if ($approval->status_approve_1 != 1 || 
                        $approval->status_approve_2 != 1 || 
                        $approval->status_approve_4 != 1) {
                        return response()->json(['error' => 'Cannot close. Required approvals must be approved for Non Project.'], 422);
                    }
                }

            }

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
                $capex->category = $validated['category'];

                // Handle file upload for Close status
                if ($validated['status_capex'] === 'Close' && $request->hasFile('file_asset')) {
                    $file = $request->file('file_asset');
                    $originalFileName = $file->getClientOriginalName();
                    $filePath = $file->storeAs('public/uploads/sapFiles', $originalFileName);
                    $capex->file_asset = str_replace('public/', '', $filePath);

                    Excel::import(new AssetImport($capex->id_capex), $file);

                }

                // Update or create ReportTax record
                if (!empty($validated['capdate']) || !empty($validated['capdoc']) ) {
                    $capex->cap_date = $validated['capdate'] ?? null;
                    $capex->cap_doc = $validated['capdoc'] ?? null;
                }

                // Simpan Capex dan CapexStatus
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
        } else if ($flag === 'add-engineer') {
            // Validasi data input
            $request->validate([
                'nama' => 'required|string|max:50',
            ]);

            $engineer = CapexEngineer::addEngineer($request->all());


            // Mengembalikan response sukses atau redirect
            return response()->json([
                'success' => true,
                'message' => 'Description successfully added.',
                'data' => $engineer,
            ]);
        } else if ($flag === 'get-sap-data') {
            $request->validate([
                'id_capex' => 'required|string',
            ]);

            $capex = DB::table('t_master_capex')->where('id_capex', $request->id_capex)->first();
            if (!$capex || empty($capex->wbs_number)) {
                return response()->json(['error' => 'WBS number tidak ditemukan'], 404);
            }

            $jsonResponse = $this->getDataFromSAP();
            if (empty($jsonResponse->original['IT_EXPORT'])) {
                return response()->json(['error' => 'Data SAP tidak ditemukan'], 404);
            }

            $txtData = $jsonResponse->original;

            function getBaseWbs($wbs)
            {
                if (strpos($wbs, '/') !== false) {

                    return substr($wbs, 0, strpos($wbs, '/'));
                }

                if (strpos($wbs, '-') !== false) {

                    $parts = explode('-', $wbs);
                    if (count($parts) >= 2) {
                        return $parts[0] . '-' . $parts[1];
                    }


                    return substr($wbs, 0, strpos($wbs, '-') + 1);
                }

                return substr($wbs, 0, 1); // Ambil karakter pertama
            }

            // Filter data berdasarkan WBS
            $dataToProcess = array_filter($txtData['IT_EXPORT'], function ($data) use ($capex) {
                if (isset($data['WBS'])) {
                    // Ambil bagian pertama dari WBS
                    $baseWbs = getBaseWbs($data['WBS']);
                    $capexWbs = getBaseWbs($capex->wbs_number);

                    return $baseWbs === $capexWbs;
                }
                return false;
            });

            if (empty($dataToProcess)) {
                return response()->json(['message' => 'Tidak ada data yang cocok untuk WBS ini']);
            }

            // Mulai transaction untuk memastikan data tersimpan dengan aman
            DB::beginTransaction();
            try {
                foreach ($txtData['IT_EXPORT'] as $data) {
                    $baseWbs = getBaseWbs($data['WBS']);
                    $capexWbs = getBaseWbs($capex->wbs_number);

                    if ($baseWbs === $capexWbs) {
                        if (isset($data['WOGBTR']) && $data['WOGBTR'] != 0) {
                            $exists = DB::table('t_capex_pocommitment_tail')
                                ->where('purchasing_doc', $data['REFBN'])
                                ->where('reference_item', $data['RFPOS'])
                                ->where('fiscal_year', $data['GJAHR'])
                                ->exists();

                            if (!$exists) {
                                DB::table('t_capex_pocommitment_tail')->insert([
                                    'purchasing_doc' => $data['REFBN'],
                                    'reference_item' => $data['RFPOS'],
                                    'doc_date' => $data['BLDAT'],
                                    'fiscal_year' => $data['GJAHR'],
                                    'no_material' => $data['MATNR'],
                                    'material_desc' => $data['SGTXT'],
                                    'qty' => $data['GESMNG'],
                                    'uom' => $data['MEINH'],
                                    'value_trancurr' => $data['WTGBTR'],
                                    'tcurr' => $data['TWAER'],
                                    'valuein_obj' => $data['WOGBTR'],
                                    'cost_element' => $data['SAKTO'],
                                    'wbs' => $data['WBS'],
                                    'created_at' => now(),
                                    'updated_at' => now(),
                                ]);
                            }
                        }
                    }
                }

                DB::commit();
                return response()->json(['message' => 'Data berhasil disimpan']);
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json(['error' => 'Terjadi kesalahan saat menyimpan data'], 500);
            }
        }

        return response()->json(['error' => 'Invalid flag specified.'], 400);
    }

    public function updateStatus(Request $request, $id)
    {
        $approval = Approve::findOrFail($id);
        $capex = $approval->capex; // Mengambil data dari relasi

        // Logika Validasi
        if ($capex->wbs_capex === 'Project') {
            if (
                $approval->status_approve_1 != 1 ||
                $approval->status_approve_2 != 1 ||
                $approval->status_approve_3 != 1 ||
                $approval->status_approve_4 != 1
            ) {
                return redirect()->back()->withErrors(['status' => 'Cannot close. All approval statuses must be approved for Project.']);
            }
        } elseif ($capex->wbs_capex === 'Non Project') {
            if (
                $approval->status_approve_1 != 1 ||
                $approval->status_approve_2 != 1 ||
                $approval->status_approve_4 != 1
            ) {
                return redirect()->back()->withErrors(['status' => 'Cannot close. Required approval statuses must be approved for Non Project.']);
            }
        }

        // Update Status to Close
        $approval->update(['status_capex' => 'close']);

        return redirect()->back()->with('success', 'Status updated to Close successfully.');
    }


    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        // Mengambil nilai flag dari permintaan
        $flag = $request->input('flag');

        if ($flag === 'budget') {

            $capex = Capex::findOrFail($id);
            $canViewBtn = $capex->status_capex !== 'Close';

            if ($request->ajax()) {
                $status = $request->get('status', 1);

                $query = CapexBudget::query()
                    ->where('id_capex', $id)
                    ->where('status', $status);

                $query = CapexBudget::getStatus($id, $status);

                return DataTables::of($query)
                    ->with('meta', [
                        'canViewBtn' => $canViewBtn
                    ])
                    ->addColumn('action', function ($row) {
                        return view('capex/datatables/actionbtnbudget', ['row' => $row]);
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
        } else if ($flag === 'progress') {
            $capex = Capex::findOrFail($id);
            $canViewBtn = $capex->status_capex !== 'Close';

            if ($request->ajax()) {
                $status = $request->get('status', 1);

                $query = CapexProgress::query()
                    ->where('id_capex', $id)
                    ->where('status', $status);

                $query = CapexProgress::getStatus($id, $status);

                return DataTables::of($query)
                    ->with('meta', [
                        'canViewBtn' => $canViewBtn
                    ])
                    ->addColumn('action', function ($row) {
                        return view('capex/datatables/actionbtnprogress', ['row' => $row]);
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }

            return view('capex.budget-capex', compact('capex', 'canCreateBudget'));  // Pastikan compact mengirimkan canCreateBudget ke view

        } else if ($flag === 'porelease') {
            $capex = Capex::findOrFail($id);
            $canViewBtn = $capex->status_capex !== 'Close';

            if ($request->ajax()) {
                $status = $request->get('status', 1);

                $query = CapexPOrelease::query()
                    ->where('id_capex', $id)
                    ->where('status', $status);

                $query = CapexPOrelease::getStatus($id, $status);

                return DataTables::of($query)
                    ->with('meta', [
                        'canViewBtn' => $canViewBtn
                    ])

                    ->addColumn('action', function ($row) {
                        return view('capex/datatables/actionbtnporelease', ['row' => $row]);
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
        } else if ($flag === 'completion') {
            $capex = Capex::findOrFail($id);
            $canViewBtn = $capex->status_capex !== 'Close';

            if ($request->ajax()) {
                $status = $request->get('status', 1);

                $query = CapexCompletion::query()
                    ->where('id_capex', $id)
                    ->where('status', $status);

                $query = CapexCompletion::getStatus($id, $status);

                return DataTables::of($query)

                    ->with('meta', [
                        'canViewBtn' => $canViewBtn
                    ])
                    ->addColumn('action', function ($row) {
                        return view('capex/datatables/actionbtncompletion', ['row' => $row]);
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
        } else if ($flag === 'status') {

            $query = CapexStatus::query()->where('id_capex', $id);

            return DataTables::of($query)
                ->make(true);
        } else if ($flag === 'pocommitment') {

            $id_porelease = $request->get('id_porelease');
            $status = $request->get('status', 1);

            $query = DB::table('t_capex_pocommitment_tail')
                ->where('id_capex_porelease', $id_porelease)
                ->where('status', $status);

            return DataTables::of($query)
                ->addIndexColumn()
                ->make(true);
        } else if ($flag === 'engineer') {
            $engineers = CapexEngineer::all();  // Ambil semua data engineer

            return DataTables::of($engineers)->make(true);
        } else if ($flag === 'view-pdf') {
            $capex = Capex::findOrFail($id);

            $filename = $capex->file_asset;
            $path = storage_path('app/public/uploads/sapFiles/' . $filename);

            // Jika file tidak ditemukan, kembalikan 404
            if (!file_exists($path)) {
                abort(404, 'File not found.');
            }

            // Tampilkan file PDF
            return response()->file($path);
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

            $porelease = CapexPOrelease::findOrFail($id);
            $porelease->status = 0;
            $porelease->save();

            $porelease = Capex::findOrFail($porelease->id_capex);

            $porelease->PO_release = null;
            $porelease->save();

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

    public function getDataFromSAP()
    {
        $flag = 'ZFM_GET_CJE3';
        $sapClient = 'Client=PRD-300';
        $sapReqUrl = 'http://eows.ecogreenoleo.co.id/general.php?';
        $sapFm = '&FM=' . $flag;
        $input_1 = '&PSPHI=P-1525-01';
        $eobUrl = $sapReqUrl . $sapClient . $sapFm . $input_1;

        $ch = curl_init($eobUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            $error_message = curl_error($ch);
            return response()->json(['error' => $error_message], 500);
        }

        curl_close($ch);

        $data = json_decode($response, true);


        // Tampilkan data di browser
        return response()->json($data);
    }
}
