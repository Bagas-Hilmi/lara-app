<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CapexStatus extends Model
{
    use HasFactory;
    protected $table = 't_capex_status';
    protected $primaryKey = 'id_capex_status';
    public $timestamps = true;

    protected $fillable = [
        'id_capex_status',
        'id_capex',
        'status',

    ];

    public static function get_dtCapexStatus()
    {
        $query = DB::table('t_capex_status')
            ->select([
                'id_capex_status',
                'id_capex',
                'status',
                'changed_at',
            ])
            ->orderby('asc');
        return $query->get();
    }

    public function Capex()
    {
        return $this->belongsTo(Capex::class, 'id_capex');
    }
}
