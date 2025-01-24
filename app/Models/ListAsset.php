<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ListAsset extends Model
{
    use HasFactory;

    protected $table = 't_list_asset';

    protected $fillable = [
        'id_capex',
        'no',
        'cost_center',
        'tag_number',
        'asset_number',
        'asset_number_2',
        'asset_class_id',
        'asset_class_name',
        'life_k',
        'life_f',
        'asset_name',
        'plant',
        'qty',
        'uom',
        'status',
    ];
}
