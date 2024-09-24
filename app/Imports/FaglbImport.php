<?php

namespace App\Imports;

use App\Models\FaglbTail; // Ganti dengan model yang sesuai
use Maatwebsite\Excel\Concerns\ToModel;

class FaglbImport implements ToModel
{
    public function model(array $row)
    {
        return new FaglbTail([
            'asset' => $row[0],
            'sub_number' => $row[1],
            'posting_date' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[2]),
            'document_number' => $row[3],
            'reference_key' => $row[4],
            'material' => $row[5],
            'business_area' => $row[6],
            'quantity' => $row[7],
            'base_unit_of_measure' => $row[8],
            'document_type' => $row[9],
            'posting_key' => $row[10],
            'document_currency' => $row[11],
            'amount_in_doc_curr' => $row[12],
            'local_currency' => $row[13],
            'amount_in_lc' => $row[14],
            'local_currency_2' => $row[15],
            'amount_in_loc_curr_2' => $row[16],
            'text' => $row[17],
            'assignment' => $row[18],
            'profit_center' => $row[19],
            'wbs_element' => $row[20],
        ]);
    }
}

