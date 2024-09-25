<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zlis1Tail extends Model
{
    use HasFactory;

    // Tentukan nama tabel jika berbeda dari konvensi penamaan
    protected $table = 't_zlis1_tail';

    // Tentukan kolom yang bisa diisi
    protected $fillable = [
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
        'value_tran_curr',
        'currency',
        'document_date',
        'posting_date',
        'purchasing_document',
        'supplier',
        'name_1',
        'asset',
        'sub_number',
        'cost_center',
        'gl_account',
        'user_name',
        'reversed_with',
        'wbs_level_2',
        'wbs_element_2',
        'id_head',
    ];

    public function faglbHead()
    {
        return $this->belongsTo(Faglb::class, 'id_head'); // Menghubungkan ke model FaglbHead
    }
}
