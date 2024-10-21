<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Report extends Model
{
    use HasFactory;
    protected $table = 't_report_cip';
    protected $primaryKey = 'id_report_cip';
    public $timestamps = true;
    
    protected $fillable = [
        'id_capex',
        'fa_doc',
        'date',
        'settle_doc',
        'material',
        'description',
        'qty',
        'uom',
        'amount_rp',
        'amount_us',

    ];

    public static function get_dtReportCip()
    {
        $query = DB::table('t_capex_budget')
            ->select([
                'id_report_cip',
                'id_capex',
                'fa_doc',
                'date',
                'settle_doc',
                'material',
                'description',
                'qty',
                'uom',
                'amount_rp',
                'amount_us',
                'created_at',
                'updated_at'
            ])
            ->orderby('asc');
            return $query->get();
        }

    public function Capex()
    {
        return $this->belongsTo(Capex::class, 'id_capex');
    }
    
}
