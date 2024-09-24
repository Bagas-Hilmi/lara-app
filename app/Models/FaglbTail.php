<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FaglbTail extends Model
{
    use HasFactory;

    // Tentukan nama tabel jika berbeda dari konvensi penamaan
    protected $table = 't_faglb_tail';

    // Tentukan kolom yang bisa diisi
    protected $fillable = [
        'Asset',
        'Sub-number',
        'Posting Date',
        'Document Number',
        'Reference Key',
        'Material',
        'Business Area',
        'Quantity',
        'Base Unit of Measure',
        'Document Type',
        'Posting Key',
        'Document currency',
        'Amount in doc. curr.',
        'Local Currency',
        'Amount in LC',
        'Local Currency 2',
        'Amount in loc.curr.2',
        'Text',
        'Assignment',
        'Profit Center',
        'WBS element',
        'period',
    ];
}

