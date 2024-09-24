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

class FaglbController extends Controller
{
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

        // Tambahkan log untuk memeriksa hasil query
        Log::info('Periods:', $periods->toArray());

        return response()->json(['periods' => $periods]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {


        // Validasi data jika diperlukan
        $request->validate([
            'faglb' => 'required|file|mimes:xlsx,xls,csv',
            'zlis1' => 'required|file|mimes:xlsx,xls,csv',
            'period' => 'required',
            'id_ccb' => 'required',
        ]);


        Log::info('Table structure', [
            'faglb' => DB::getSchemaBuilder()->getColumnListing('t_faglb_tail'),
            'zlis1' => DB::getSchemaBuilder()->getColumnListing('t_zlis1_tail')
        ]);

        if ($request->hasFile('faglb')) {
            Log::info('FAGLB file uploaded.', ['file' => $request->file('faglb')->getClientOriginalName()]);
        } else {
            Log::error('No FAGLB file uploaded.');
        }

        // Cek apakah file ZLIS1 diupload
        if ($request->hasFile('zlis1')) {
            Log::info('ZLIS1 file uploaded.', ['file' => $request->file('zlis1')->getClientOriginalName()]);
        } else {
            Log::error('No ZLIS1 file uploaded.');
        }


        $faglbHead = new Faglb();
        $faglbHead->period = $request->input('period');
        $faglbHead->id_ccb = $request->input('id_ccb');
        $faglbHead->save();

        $this->importFaglb($request->file('faglb'), $faglbHead->id_head);

        // Panggil fungsi untuk memproses file ZLIS1
        $this->importZlis1($request->file('zlis1'), $faglbHead->id_head);

        return redirect()->back()->with('success', 'Documents uploaded successfully.');
    }

    private function importFaglb($file, $id_head)
    {
        if ($file) {
            $faglbData = Excel::toArray(new FaglbImport, $file);
            Log::info('FAGLB data read from Excel', ['count' => count($faglbData[0])]);

            // Ambil data dari sheet pertama
            $faglbRows = $faglbData[0];

            // Mengabaikan baris pertama (header)
            array_shift($faglbRows);

            foreach ($faglbRows as $index => $row) {
                try { // Asumsikan bahwa kolom pada array $row sudah sesuai dengan urutan header
                    DB::table('t_faglb_tail')->insert([
                        'Asset' => $row[0],
                        'Sub-number' => $row[1],
                        'Posting Date' => \Carbon\Carbon::parse($row[2])->format('Y-m-d'), // Mengubah format tanggal jika perlu
                        'Document Number' => $row[3],
                        'Reference Key' => $row[4],
                        'Material' => $row[5],
                        'Business Area' => $row[6],
                        'Quantity' => $row[7],
                        'Base Unit of Measure' => $row[8],
                        'Document Type' => $row[9],
                        'Posting Key' => $row[10],
                        'Document currency' => $row[11],
                        'Amount in doc. curr.' => $row[12],
                        'Local Currency' => $row[13],
                        'Amount in LC' => $row[14],
                        'Local Currency 2' => $row[15],
                        'Amount in loc.curr.2' => $row[16],
                        'Text' => $row[17],
                        'Assignment' => $row[18],
                        'Profit Center' => $row[19],
                        'WBS element' => $row[20],
                        'id_head' => $id_head, // Tambahkan foreign key id_head
                    ]);
                    Log::info("Row $index inserted successfully");
                } catch (\Exception $e) {
                    Log::error("Error inserting row $index: " . $e->getMessage());
                }
            }
        }
    }


    private function importZlis1($file, $id_head)
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

                DB::table('t_zlis1_tail')->insert([
                    'wbs_element' => $row[0],
                    'network' => $row[1],
                    'document_number' => $row[2],
                    'company_code' => $row[3],
                    'fiscal_year' => $row[4],
                    'item' => $row[5],
                    'material_document' => $row[6],
                    'material_doc_year' => $row[7],
                    'material' => $row[8],
                    'description' => $row[9],
                    'quantity' => $row[10],
                    'base_unit_of_measure' => $row[11],
                    'value_tran_curr' => $row[12],
                    'currency' => $row[13],
                    'value_tran_curr_2' => $row[14],
                    'currency_2' => $row[15],
                    'value_tran_curr_3' => $row[16],
                    'currency_3' => $row[17],
                    'document_date' => \Carbon\Carbon::createFromFormat('m/d/Y', $row[18])->format('Y-m-d'),
                    'posting_date' => \Carbon\Carbon::createFromFormat('m/d/Y', $row[19])->format('Y-m-d'),
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
                    'document_date_2' => \Carbon\Carbon::createFromFormat('m/d/Y', $row[30])->format('Y-m-d'),
                    'posting_date_2' => \Carbon\Carbon::createFromFormat('m/d/Y', $row[31])->format('Y-m-d'),
                    'user_name' => $row[32],
                    'reversed_with' => $row[33],
                    'wbs_level_2' => $row[34],
                    'wbs_element_2' => $row[35],
                    'id_head' => $id_head, // Tambahkan foreign key id_head
                ]);
            }
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
    public function update(Request $request, $id_head)
    {
        // Validasi data jika diperlukan
        $request->validate([
            'id_ccb' => 'required',
            'period' => 'required',
            'report_status' => 'required',
            'updated_by' => 'required', // Pastikan ini divalidasi
            // tambahkan validasi lain jika perlu
        ]);

        // Mengambil data dari request
        $data = [
            'id_ccb' => $request->input('id_ccb'),
            'period' => $request->input('period'),
            'report_status' => $request->input('report_status'),
            'updated_by' => $request->input('updated_by'), // Ambil dari input
        ];

        // Memperbarui data di model
        Faglb::updateDataFaglb($id_head, $data);

        return redirect()->route('faglb.index')->with('success', 'Data updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
