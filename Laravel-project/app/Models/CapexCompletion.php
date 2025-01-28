<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CapexCompletion extends Model
{
    use HasFactory;
    protected $table = 't_capex_completion_date';
    protected $primaryKey = 'id_capex_completion';

    protected $fillable = [
        'id_capex_budget',
        'id_capex',
        'date',

    ];
    public static function get_dtCapexCompletion()
    {
        $query = DB::table('t_capex_completion_date')
            ->select([
                'id_capex_completion',
                'id_capex_budget',
                'id_capex',
                'date',
                'created_at',
                'updated_at'
            ])
            ->orderBy('asc'); // 

        return $query->get(); // Mengambil semua data
    }

    public static function addCompletion($data)
    {
        $completionId = DB::table('t_capex_completion_date')->insertGetId([
            'id_capex' => $data['id_capex'],
            'date' => $data['date'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('t_master_capex')
            ->where('id_capex', $data['id_capex'])
            ->update([
                'revise_completion_date' => $data['date'],
                'updated_at' => now(),
            ]);

        // Mengembalikan data completion yang baru ditambahkan
        return DB::table('t_capex_completion_date')->where('id_capex_completion', $completionId)->first();
    }

    public static function editCompletion($id, $data)
    {
        // Update data completion berdasarkan ID
        DB::table('t_capex_completion_date')
            ->where('id_capex_completion', $id)
            ->update([
                'id_capex' => $data['id_capex'],
                'date' => $data['date'],
                'updated_at' => now(), // Jika Anda ingin mencatat waktu pembaruan
            ]);

        // Update kolom revise_completion_date di tabel t_master_capex
        DB::table('t_master_capex')
            ->where('id_capex', $data['id_capex'])
            ->update([
                'revise_completion_date' => $data['date'],
                'updated_at' => now(), // Jika Anda ingin mencatat waktu pembaruan
            ]);

        // Mengembalikan data completion yang telah diperbarui
        return DB::table('t_capex_completion_date')->where('id_capex_completion', $id)->first();
    }

    public static function getStatus($id, $status = null)
    {
        $query = DB::table('t_capex_completion_date') 
            ->join('t_master_capex', 't_capex_completion_date.id_capex', '=', 't_master_capex.id_capex')
            ->select('t_capex_completion_date.*', 't_master_capex.status_capex');

        if($id){
            $query->where('t_capex_completion_date.id_capex', $id);
        }

        if(!is_null($status)){
            $query->where('t_capex_completion_date.status', $status);
        }

        return $query->get();
    }

    public function Capex()
    {
        return $this->belongsTo(Capex::class, 'id_capex');
    }
}
