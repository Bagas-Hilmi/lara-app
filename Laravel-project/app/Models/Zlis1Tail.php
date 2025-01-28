<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zlis1Tail extends Model
{
    use HasFactory;

    protected $table = 't_zlis1_tail';

    protected $fillable = [
        'id_head',
        'wbs_element',
        'network',
        'document_number',
        'company_code',
        'fiscal_year',
        'item',
        'material_document',
        'material_doc_year',
        'material',
        'description',
        'quantity',
        'base_unit_of_measure',
        'value_tran_curr_1',
        'currency',
        'value_tran_curr_2',
        'currency_2',
        'value_tran_curr_3',
        'currency_3',
        'document_date',
        'posting_date',
        'purchasing_document',
        'supplier',
        'name_1',
        'asset',
        'sub_number',
        'cost_center',
        'gl_account',
        'document_number_2',
        'company_code_2',
        'fiscal_year_2',
        'document_date_2',
        'posting_date_2',
        'user_name',
        'reversed_with',
        'wbs_level_2',
        'wbs_element_2',
        'status',
    ];

    public function faglbHead()
    {
        return $this->belongsTo(Faglb::class, 'id_head');
    }
}
