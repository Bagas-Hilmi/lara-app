<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CapexPOcommitment extends Model
{
    use HasFactory;
    protected $table = 't_capex_pocommitment';
    protected $primaryKey = 'id_capex_pocommitment';
    public $timestamps = true; // J

    protected $fillable = [
        'id_capex',
        'doc_date',
        'wbs',
        'cost_element',
        'purchasing_doc',
        'reference_item',
        'no_material',
        'material_desc',
        'qty',
        'uom',
        'valuein_obj',
        'obj_currency',
        'value_trancurr',
        'tcurr',
    ];

    public function Capex()
    {
        return $this->belongsTo(Capex::class, 'id_capex');
    }
}
