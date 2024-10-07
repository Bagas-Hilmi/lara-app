<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Capex extends Model
{
    use HasFactory;
    protected $table = 't_master_capex'; // Nama tabel
    protected $primaryKey = 'id_capex'; // Atur primary key jika bukan 'id'
    public $timestamps = true; // J

    protected $fillable = [
        'project_desc',
        'wbs_capex',
        'remark',
        'request_number',
        'requester',
        'capex_number',
        'amount_budget',
        'budget_cos',
        'status_capex',
        'budget_type',
        'status',
        'created_by',
        'updated_by'
    ];
    public static function get_dtCapex()
    {
        $query = DB::table('t_master_capex')
            ->select([
                'id_capex',
                'project_desc',
                'wbs_capex',
                'remark',
                'request_number',
                'requester',
                'capex_number',
                'amount_budget',
                'budget_cos',
                'status_capex',
                'budget_type',
                'created_at',
                'updated_at'
            ])
            ->orderBy('asc'); // 

        return $query->get(); // Mengambil semua data
    }

    public static function updateDataCapex($id_capex, $data)
    {
        // Siapkan query untuk memperbarui data
        $query = DB::table('t_master_capex')
            ->where('id_capex', $id_capex)
            ->update([
                'project_desc' => $data['project_desc'],
                'wbs_capex' => $data['wbs_capex'],
                'remark' => $data['remark'],
                'request_number' => $data['request_number'],
                'requester' => $data['requester'],
                'capex_number' => $data['capex_number'],
                'amount_budget' => $data['amount_budget'],
                'budget_cos' => $data['budget_cos'],
                'status_capex' => $data['status_capex'],
                'budget_type' => $data['budget_type'],
                'updated_at' => now(), // Perbarui timestamp
            ]);

        return $query; // Mengembalikan hasil query (berhasil atau tidak)
    }

    public function CapexBudget()
    {
        return $this->hasMany(CapexBudget::class, 'id_capex');
    }
}
