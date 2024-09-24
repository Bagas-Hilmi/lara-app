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
        'WBS Element',
        'Network',
        'Document Number',
        'Company Code',
        'Fiscal Year',
        'Item',
        'Material Document',
        'Material Doc. Year',
        'Material',
        'Description',
        'Quantity',
        'Base Unit of Measure',
        'Value TranCurr',
        'Currency',
        'Document Date',
        'Posting Date',
        'Purchasing Document',
        'Supplier',
        'Name 1',
        'Asset',
        'Sub-number',
        'Cost Center',
        'G/L Account',
        'User Name',
        'Reversed with',
        'WBS level 2',
        'WBS Element',
        'period',
    ];
}
