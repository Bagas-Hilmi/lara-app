<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CapexProgress extends Model
{
    use HasFactory;
    protected $table = 't_capex_progress';
    protected $primaryKey = 'id_capex_progress';
    public $timestamps = true; // 

    protected $fillable = [
        'id_capex',
        'tanggal',
        'description',

    ];

    public static function get_dtCapexProgress()
    {
        $query = DB::table('t_capex_progress')
            ->select([
                'id_capex_progress',
                'id_capex',
                'tanggal',
                'description',
                'created_at',
                'updated_at'
            ])
            ->orderBy('asc'); // 

        return $query->get(); // Mengambil semua data
    }

    public static function addProgress($data)
    {
        // Gunakan query builder untuk menambahkan data
        return DB::table('t_capex_progress')->insert([
            'id_capex' => $data['id_capex'],
            'tanggal' => $data['tanggal'],
            'description' => $data['description'],
        ]);
    }

    public static function editProgress($id, $data)
    {
        $progress = DB::table('t_capex_progress')->where('id_capex_progress', $id)->first();

        if (!$progress) {
            return null; // Atau throw exception sesuai kebutuhan
        }

        DB::table('t_capex_progress')->where('id_capex_progress', $id)->update([
            'id_capex' => $data['id_capex'],
            'tanggal' => $data['tanggal'],
            'description' => $data['description'],
            'updated_at' => now(),
        ]);

        return DB::table('t_capex_progress')->where('id_capex_progress', $id)->first();
    }

    public function Capex()
    {
        return $this->belongsTo(Capex::class, 'id_capex');
    }
}
