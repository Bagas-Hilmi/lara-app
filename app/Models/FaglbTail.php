<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FaglbTail extends Model
{
    use HasFactory;

    // Nama tabel yang digunakan oleh model ini
    protected $table = 't_faglb_tail';

    // Kolom yang dapat diisi massal
    protected $fillable = [
        'id_head', // Foreign key yang terhubung ke tabel t_faglb_head
        'asset',
        'sub_number',
        'posting_date',
        'document_number',
        'reference_key',
        'material',
        'business_area',
        'quantity',
        'base_unit_of_measure',
        'document_type',
        'posting_key',
        'document_currency',
        'amount_in_doc_curr',
        'local_currency',
        'amount_in_lc',
        'local_currency_2',
        'amount_in_loc_curr_2',
        'text',
        'assignment',
        'profit_center',
        'wbs_element',
    ];

    // Relasi dengan model FaglbHead jika ada
    public function faglbHead()
    {
        return $this->belongsTo(Faglb::class, 'id_head'); // Menghubungkan ke model FaglbHead
    }
}
