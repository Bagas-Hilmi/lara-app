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
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        if ($request->ajax()) {
            
            $status = $request->get('status', 1);

            
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
            ->where('report_status', 0)
            ->where('status', 1)
            ->get();

        return response()->json(['periods' => $periods]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $flag = $request->input('flag');

        if ($flag == 'upload_documents') {
            // Mulai transaksi database
            DB::beginTransaction();
            try {
                $request->validate([
                    'faglb' => 'required|file|mimes:xlsx,xls,csv|max:2048',
                    'zlis1' => 'required|file|mimes:xlsx,xls,csv|max:2048',
                    'period' => 'required',
                    'id_ccb' => 'required',
                    'flag' => 'required|string|in:upload_documents,update_file',
                ]);

                // Logika untuk mengupload dokumen baru
                $faglbHead = new Faglb();
                $faglbHead->period = $request->input('period');
                $faglbHead->id_ccb = $request->input('id_ccb');
                $faglbHead->save();

                // Proses unggah dan import FAGLB file
                if ($request->hasFile('faglb')) {
                    $faglbFile = $request->file('faglb');
                    $faglbFileName = $faglbFile->getClientOriginalName();
                    $faglbFilePath = $faglbFile->storeAs('uploads/faglb', $faglbFileName);
                    $faglbHead->faglb_filename = $faglbFileName;

                    try {
                        $this->importFaglb($faglbFilePath, $faglbHead->id_head); // Import ke database
                    } catch (\Exception $e) {
                        Storage::delete($faglbFilePath); // Hapus file jika ada error
                        throw new \Exception('Gagal mengimpor file FAGLB: ' . $e->getMessage());
                    }
                }

                // Proses unggah dan import ZLIS1 file
                if ($request->hasFile('zlis1')) {
                    $zlis1File = $request->file('zlis1');
                    $zlis1FileName = $zlis1File->getClientOriginalName();
                    $zlis1FilePath = $zlis1File->storeAs('uploads/zlis1', $zlis1FileName);
                    $faglbHead->zlis1_filename = $zlis1FileName;

                    try {
                        $this->importZlis1($zlis1FilePath, $faglbHead->id_head); // Import ke database
                    } catch (\Exception $e) {
                        Storage::delete($zlis1FilePath); // Hapus file jika ada error
                        throw new \Exception('Gagal mengimpor file ZLIS1: ' . $e->getMessage());
                    }
                }

                // Simpan data ke database
                $faglbHead->save();

                // Commit transaksi jika semua berhasil
                DB::commit();
                return response()->json(['success' => true, 'message' => 'Data FAGLB dan ZLIS1 berhasil diimpor!']);
            } catch (\Exception $e) {
                // Rollback jika terjadi kesalahan
                DB::rollBack();
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
        } elseif ($flag == 'update_file') {
            DB::beginTransaction();
            try {
                $request->validate([
                    'faglb' => 'sometimes|file|mimes:xlsx,xls,csv|max:2048', // Optional jika hanya ingin update salah satu
                    'zlis1' => 'sometimes|file|mimes:xlsx,xls,csv|max:2048',
                    'id_head' => 'required|integer',
                ]);

                $id = $request->input('id_head');
                $faglbHead = Faglb::findOrFail($id);

                // Update FAGLB file jika ada
                if ($request->hasFile('faglb')) {
                    $faglbFile = $request->file('faglb');
                    $faglbFileName = $faglbFile->getClientOriginalName();
                    $faglbFilePath = $faglbFile->storeAs('uploads/faglb', $faglbFileName);
                    $faglbHead->faglb_filename = $faglbFileName;

                    try {
                        $this->importFaglb($faglbFilePath, $id); // Import file
                    } catch (\Exception $e) {
                        Storage::delete($faglbFilePath); // Hapus file jika ada error
                        throw new \Exception('Gagal mengimpor file FAGLB: ' . $e->getMessage());
                    }
                }

                // Update ZLIS1 file jika ada
                if ($request->hasFile('zlis1')) {
                    $zlis1File = $request->file('zlis1');
                    $zlis1FileName = $zlis1File->getClientOriginalName();
                    $zlis1FilePath = $zlis1File->storeAs('uploads/zlis1', $zlis1FileName);
                    $faglbHead->zlis1_filename = $zlis1FileName;

                    try {
                        $this->importZlis1($zlis1FilePath, $id); // Import file
                    } catch (\Exception $e) {
                        Storage::delete($zlis1FilePath); // Hapus file jika ada error
                        throw new \Exception('Gagal mengimpor file ZLIS1: ' . $e->getMessage());
                    }
                }

                $faglbHead->save();
                DB::commit();

                return response()->json(['success' => true, 'message' => 'File berhasil diperbarui']);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
        }
    }

    function formatAmount($amount) 
    {
        // Hapus spasi jika ada
        $amount = trim($amount);
        
        // Hapus pemisah ribuan (titik)
        $amount = str_replace('.', '', $amount);
        
        // Ganti koma desimal dengan titik
        $amount = str_replace(',', '.', $amount);
        
        // Convert ke float
        return (float) $amount;
    }

    public function importFaglb($file, $id_head)
    {
        function formatAmount($amount) {
            if(empty($amount)) return 0;
            
            $amount = trim($amount);
            // Hapus pemisah ribuan (titik)
            $amount = str_replace('.', '', $amount);
            // Ganti koma desimal dengan titik
            $amount = str_replace(',', '.', $amount);
            
            return (float) $amount;
        }
        
        if ($file) {
            $faglbData = Excel::toArray(new FaglbImport, $file);


            // Ambil data dari sheet pertama
            $faglbRows = $faglbData[0];

            // Mengabaikan baris pertama (header)
            array_shift($faglbRows);

            // Hapus data lama dari t_faglb_tail berdasarkan id_head sebelum memasukkan data baru
            DB::table('t_faglb_tail')->where('id_head', $id_head)->delete();

            foreach ($faglbRows as $index => $row) {

                DB::table('t_faglb_tail')->insert([
                    'id_head' => $id_head,
                    'Asset' => $row[0],
                    'sub_number' => $row['2'],
                    'posting_date' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[2])->format('Y-m-d'),
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

            DB::table('t_zlis1_tail')->where('id_head', $id_head)->delete();

            // Menyimpan data ke tabel t_zlis1_tail
            foreach ($dataZlis1[0] as $row) {
                // Pastikan untuk mengabaikan baris pertama yang berisi judul
                if ($row[0] == 'WBS Element') {
                    continue; // Skip the header row
                }

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
                    'document_date' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[18])->format('Y-m-d'),
                    'posting_date' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[19])->format('Y-m-d'),
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
                    'document_date_2' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[30])->format('Y-m-d'),
                    'posting_date_2' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[31])->format('Y-m-d'),
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
    public function show(string $id, Request $request)
    {
        $flag = $request->input('flag');

        if ($flag == 'show_faglb') {
            $faglbData = FaglbTail::where('id_head', $id)->get();
            return view('faglb.show.faglb', compact('faglbData'));
        } elseif ($flag == 'show_zlis1') {
            $zlis1Data = Zlis1Tail::where('id_head', $id)->get();
            return view('faglb.show.zlis1', compact('zlis1Data'));
        }

        // Jika flag tidak dikenali, Anda bisa mengarahkan ke halaman 404 atau memberikan pesan kesalahan
        return redirect()->route('faglb.index')->with('error', 'Data tidak ditemukan.');
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
    public function update(Request $request)
    {
        // 
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Temukan record di t_faglb_head
        $faglbHead = Faglb::findOrFail($id);

        // Ubah status di t_faglb_head menjadi 0
        $faglbHead->status = 0;
        $faglbHead->save();

        // Temukan semua record terkait di t_faglb_tail dan t_zlis1_tail
        $idHead = $faglbHead->id_head; 

        // Ubah status di t_faglb_tail
        DB::table('t_faglb_tail')
            ->where('id_head', $idHead)
            ->update(['status' => 0]);

        DB::table('t_zlis1_tail')
            ->where('id_head', $idHead)
            ->update(['status' => 0]);

        DB::table('t_report_cip')
            ->where('id_head', $idHead)
            ->update(['status' => 0]);

        return response()->json(['success' => true]);
    }
}
