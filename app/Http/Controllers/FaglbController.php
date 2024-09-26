<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Faglb;
use App\Models\FaglbTail;
use App\Models\Zlis1Tail;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\FaglbImport;
use App\Imports\Zlis1Import;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class FaglbController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth'); // Pastikan middleware auth diterapkan
    }
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        if ($request->ajax()) {
            // Ambil status dari permintaan, dengan nilai default 1 jika tidak ada
            $status = $request->get('status', 1);

            // Buat query berdasarkan model Faglb
            $query = Faglb::query()->where('status', $status);

            return DataTables::of($query)
                ->addColumn('action', function ($row) {
                    return view('faglb/datatables/actionbtn', ['row' => $row]);
                })
                ->editColumn('report_status', function ($row) {
                    return $row->report_status == 0 ? 'Unreleased' : 'Release';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('faglb.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $periods = DB::table('t_cip_cum_bal')
            ->select('id_ccb', 'period_cip')
            ->where('report_status', 1)
            ->get();

        return response()->json(['periods' => $periods]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'faglb' => 'required|file|mimes:xlsx,xls,csv',
            'zlis1' => 'required|file|mimes:xlsx,xls,csv',
            'period' => 'required',
            'id_ccb' => 'required',
        ]);

        $faglbHead = new Faglb();
        $faglbHead->period = $request->input('period');
        $faglbHead->id_ccb = $request->input('id_ccb');
        $faglbHead->save();

        $faglbFilePath = $request->file('faglb')->store('uploads/faglb');

        $faglbData = Excel::toArray(new FaglbImport(), $faglbFilePath);

        $this->importFaglb($faglbFilePath, $faglbHead->id_head);

        $zlis1FilePath = $request->file('zlis1')->store('uploads/zlis1');

        $zlis1Data = Excel::toArray(new Zlis1Import(), $zlis1FilePath);

        $this->importZlis1($zlis1FilePath, $faglbHead->id_head);

        return redirect()->back()->with('success', 'Data FAGLB dan ZLIS1 berhasil diimpor!');
    }

    public function importFaglb($file, $id_head)
    {
        if ($file) {
            $faglbData = Excel::toArray(new FaglbImport, $file);

            // Ambil data dari sheet pertama
            $faglbRows = $faglbData[0];

            // Mengabaikan baris pertama (header)
            array_shift($faglbRows);

            foreach ($faglbRows as $index => $row) {
                // Pastikan format tanggal dan nilai yang valid
                $postingDate = \Carbon\Carbon::parse($row[2])->format('Y-m-d');

                DB::table('t_faglb_tail')->insert([
                    'id_head' => $id_head,
                    'Asset' => $row[0],
                    'sub_number' => $row['2'],
                    'posting_date' => \Carbon\Carbon::parse($row[2])->format('Y-m-d'),
                    'document_number' => $row[3],
                    'reference_key' => $row[4],
                    'material' => $row[5],
                    'business_area' => $row[6],
                    'quantity' => $row[7],
                    'base_unit_of_measure' => $row[8],
                    'document_type' => $row[9],
                    'posting_key' => $row[10],
                    'document_currency' => $row[11],
                    'amount_in_doc_curr' => $row[12],
                    'local_currency' => $row[13],
                    'amount_in_lc' => $row[14],
                    'local_currency_2' => $row[15],
                    'amount_in_loc_curr_2' => $row[16],
                    'text' => $row[17],
                    'assignment' => $row[18],
                    'profit_center' => $row[19],
                    'wbs_element' => $row[20],
                ]);
            }
        }
    }

    public function importZlis1($file, $id_head)
    {
        if ($file) {
            // Import data ZLIS1
            $dataZlis1 = Excel::toArray(new Zlis1Import, $file);

            // Menyimpan data ke tabel t_zlis1_tail
            foreach ($dataZlis1[0] as $row) {
                // Pastikan untuk mengabaikan baris pertama yang berisi judul
                if ($row[0] == 'WBS Element') {
                    continue; // Skip the header row
                }

                Log::info($row);



                DB::table('t_zlis1_tail')->insert([
                    'id_head' => $id_head,
                    'wbs_element' => $row[0],
                    'network' => $row[1],
                    'document_number' => $row[2],
                    'company_code' => $row[3],
                    'fiscal_year' => $row[4],
                    'item' => $row[5],
                    'material_document' => $row[6],
                    'material_doc_year' => !empty($row[7]) ? (int) $row[7] : null, // Pastikan menjadi int jika tipe data INT
                    'material' => $row[8],
                    'description' => $row[9],
                    'quantity' => $row[10],
                    'base_unit_of_measure' => $row[11],
                    'value_tran_curr_1' => $row[12],
                    'currency' => $row[13],
                    'value_tran_curr_2' => $row[14],
                    'currency_2' => $row[15],
                    'value_tran_curr_3' => $row[16],
                    'currency_3' => $row[17],
                    'document_date' => \Carbon\Carbon::parse($row[18])->format('Y-m-d'),
                    'posting_date' => \Carbon\Carbon::parse($row[19])->format('Y-m-d'),
                    'purchasing_document' => $row[20],
                    'supplier' => $row[21],
                    'name_1' => $row[22],
                    'asset' => $row[23],
                    'sub_number' => $row[24],
                    'cost_center' => $row[25],
                    'gl_account' => $row[26],
                    'document_number_2' => $row[27],
                    'company_code_2' => $row[28],
                    'fiscal_year_2' => $row[29],
                    'document_date_2' => \Carbon\Carbon::parse($row[30])->format('Y-m-d'),
                    'posting_date_2' => \Carbon\Carbon::parse($row[31])->format('Y-m-d'),
                    'user_name' => $row[32],
                    'reversed_with' => $row[33],
                    'wbs_level_2' => $row[34],
                    'wbs_element_2' => $row[35],
                ]);
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Ambil data dari t_faglb_tail berdasarkan id
        $faglbData = FaglbTail::where('id_head', $id)->get();


        // Kembalikan view dengan data yang diambil
        return view('faglb.show.faglb', compact('faglbData'));
    }

    public function showZlis1(string $id)
    {
        // Ambil data ZLIS1 berdasarkan id_head
        $zlis1Data = Zlis1Tail::where('id_head', $id)->get();

        return view('faglb.show.zlis1', compact('zlis1Data'));
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
    public function update(Request $request, $id)
    {
        $request->validate([
            'faglb' => 'nullable|file|mimes:xlsx,xls,csv',
            'zlis1' => 'nullable|file|mimes:xlsx,xls,csv',
        ]);

        // Temukan record berdasarkan ID
        $faglbHead = Faglb::findOrFail($id);

        // Proses file FAGLB
        if ($request->hasFile('faglb')) {
            if ($faglbHead->faglb_file_path) {
                Storage::delete($faglbHead->faglb_file_path);
            }
            $faglbFilePath = $request->file('faglb')->store('uploads/faglb');
            $faglbHead->faglb_file_path = $faglbFilePath;
        }

        // Proses file ZLIS1
        if ($request->hasFile('zlis1')) {
            if ($faglbHead->zlis1_file_path) {
                Storage::delete($faglbHead->zlis1_file_path);
            }
            $zlis1FilePath = $request->file('zlis1')->store('uploads/zlis1');
            $faglbHead->zlis1_file_path = $zlis1FilePath;
        }

        $faglbHead->status = 0; // Mengubah status
        $faglbHead->save();

        return response()->json(['success' => 'File berhasil diganti']);
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $faglb = Faglb::findOrFail($id);
        $faglb->status = 0;
        $faglb->save();

        return response()->json(['success' => true]);
    }
}
