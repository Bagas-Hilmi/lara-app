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
        'carried_over', 
        'amount',
        'actual_ytd',
        'balance',
    ];

    public static function getReportCategoryData()
    {

        // Ambil data dari t_master_capex yang aktif
        $categories = DB::table('t_master_capex')
            ->select('id_capex', 'category', 'project_desc', 'capex_number', 'total_budget', 'budget_type', 'status')
            ->where('status', 1)
            ->where('status_capex', 'On Progress') 
            ->distinct()
            ->get();

        // Proses insert/update/delete
        foreach ($categories as $category) {
            DB::table('t_report_category')
                ->updateOrInsert(
                    ['id_capex' => $category->id_capex],
                    [
                        'category' => $category->category,
                        'project' => $category->project_desc,
                        'number' => $category->capex_number,
                        'budget' => strtolower($category->budget_type) == 'budgeted' ? $category->total_budget : null,
                        'unbudget' => strtolower($category->budget_type) == 'unbudgeted' ? $category->total_budget : null,  
                        'status' => $category->status, 
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
        }

        // Hapus data di report_category yang statusnya 0 di master_capex
        DB::table('t_report_category')
            ->whereNotIn(
                'id_capex',
                DB::table('t_master_capex')
                    ->where('status', 1)
                    ->pluck('id_capex')
            )
            ->delete();

        // Update amount 
        DB::table('t_report_category')
            ->where('status', 1) // Pastikan hanya update data aktif
            ->update([
                'amount' => DB::raw('IFNULL(budget, 0) + IFNULL(unbudget, 0) + IFNULL(carried_over, 0)')
            ]);

        // Update balance
        DB::table('t_report_category')
            ->where('status', 1) // Pastikan hanya update data aktif
            ->update([
                'balance' => DB::raw('IFNULL(amount, 0) - IFNULL(actual_ytd, 0)')
            ]);

        // Return data yang diurutkan
        return DB::table('t_report_category')
            ->where('status', 1) // Hanya ambil data aktif
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
