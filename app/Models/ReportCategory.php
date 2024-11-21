<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReportCategory extends Model
{
    use HasFactory;

    protected $table = 't_report_category';
    protected $primaryKey = 'id_report_category';
    public $timestamps = true;

    protected $fillable = [
        'id_capex',
        'category',
        'project',
        'number',
        'budget',
        'unbudget',
        'carried_over', // Ditambahkan ke fillable karena ada di select
        'amount',
        'actual_ytd',
        'balance',
    ];

    public static function getReportCategoryData()
    {
        DB::beginTransaction();
            $categories = DB::table('t_master_capex')
                ->select('id_capex', 'category', 'project_desc', 'capex_number', 'amount_budget')
                ->where('status', 1)
                ->distinct() // Menghilangkan data duplikat
                ->get();

            foreach ($categories as $category) {
                DB::table('t_report_category')
                    ->updateOrInsert(
                        ['id_capex' => $category->id_capex], // kriteria pencarian
                        [
                            'category' => $category->category,
                            'project' => $category->project_desc,
                            'number' => $category->capex_number,
                            'budget' => $category->amount_budget,
                            // Ganti nilai statis dengan perhitungan atau ambil dari sumber lain
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );
            }

            DB::commit();

            return DB::table('t_report_category')
                ->orderByRaw("CASE category
                WHEN 'General Operation' THEN 1
                WHEN 'IT' THEN 2
                WHEN 'Environment' THEN 3
                WHEN 'Safety' THEN 4
                WHEN 'Improvement Plant efficiency' THEN 5
                WHEN 'Invesment' THEN 6
                ELSE 7 END")
                ->get();
           
    }

    public static function getCategory()
    {
        return DB::table('t_report_category')
        ->select('category')
        ->orderByRaw("FIELD(category, 'General Operation', 'IT', 'Environment', 'Safety')") // Urutan khusus
        ->get();

    }



    public function capex()
    {
        return $this->belongsTo(Capex::class, 'id_capex', 'id_capex');
    }
}
