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
        $categories = DB::table('t_master_capex')
            ->select('id_capex', 'category', 'project_desc', 'capex_number', 'total_budget', 'budget_type', 'status', 'status_capex')
            ->where('status', 1)
            ->where('status_capex', 'On Progress')  
            ->distinct()
            ->get();

        foreach ($categories as $category) {
            // Ambil tahun dari capex_number menggunakan regex
            preg_match('/\d{4}$/', $category->capex_number, $matches);
            $capex_year = $matches[0] ?? null; // Jika tidak ditemukan, nilainya null

            $is_carried_over = ((int)$capex_year < date('Y'));

            // Logika untuk kolom
            $carried_over = $is_carried_over ? $category->total_budget : null;
            $budget = !$is_carried_over && strtolower($category->budget_type) == 'budgeted' ? $category->total_budget : null;
            $unbudget = !$is_carried_over && strtolower($category->budget_type) == 'unbudgeted' ? $category->total_budget : null;

            $actual_ytd = DB::table('t_report_cip')
                ->where('id_capex', $category->id_capex)
                ->sum('amount_us');

            // Insert atau update data ke t_report_category hanya jika status_capex adalah 'On Progress'
            DB::table('t_report_category')
                ->updateOrInsert(
                    ['id_capex' => $category->id_capex],
                    [
                        'category' => $category->category,
                        'project' => $category->project_desc,
                        'number' => $category->capex_number,
                        'budget' => $budget,
                        'unbudget' => $unbudget,
                        'carried_over' => $carried_over,
                        'actual_ytd' => $actual_ytd,
                        'status' => $category->status,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
        }

        // Menghapus entri t_report_category yang tidak ada di t_master_capex dengan status_capex = 'On Progress'
        DB::table('t_report_category')
            ->whereNotIn(
                'id_capex',
                DB::table('t_master_capex')
                    ->where('status', 1)
                    ->where('status_capex', 'On Progress') 
                    ->pluck('id_capex')
            )
            ->delete();

        // Update jumlah total kolom amount
        DB::table('t_report_category')
            ->where('status', 1)
            ->update([
                'amount' => DB::raw('IFNULL(budget, 0) + IFNULL(unbudget, 0) + IFNULL(carried_over, 0)')
            ]);

        // Update kolom balance
        DB::table('t_report_category')
            ->where('status', 1)
            ->update([
                'balance' => DB::raw('IFNULL(amount, 0) - IFNULL(actual_ytd, 0)')
            ]);

            return DB::table('t_report_category')
                ->where('status', 1) 
                ->orderByRaw("CASE category
        WHEN 'General Operation' THEN 1
        WHEN 'IT' THEN 2
        WHEN 'Environment' THEN 3
        WHEN 'Safety' THEN 4
        WHEN 'Improvement Plant efficiency' THEN 5
        WHEN 'Invesment' THEN 6
        ELSE 7 END");
    }

    public static function getCategory()
    {
        $categories = DB::table('t_master_capex')
            ->select('category')
            ->where('status', 1)
            ->where('status_capex', 'On Progress')
            ->distinct()
            ->pluck('category')
            ->toArray();

        return $categories;
    }




    public function capex()
    {
        return $this->belongsTo(Capex::class, 'id_capex', 'id_capex');
    }
}
