<?php

namespace App\Imports;

use App\Models\Zlis1Tail;
use Maatwebsite\Excel\Concerns\ToModel;

class Zlis1Import implements ToModel
{
    public function model(array $row)
    {
        return new Zlis1Tail([
            'wbs_element' => $row[0],  
            'network' => $row[1],  
            'document_number' => $row[2],  
            'company_code' => $row[3],
            'fiscal_year' => $row[4],
            'item' => $row[5], 
            'material_document' => $row[6],
            'material_doc_year' => $row[7],
            'material' => $row[8],
            'description' => $row[9],
            'quantity' => $row[10], 
            'base_unit_of_measure' => $row[11],
            'value_tran_curr_1' => $row[12],
            'currency' => $row[13],
            'value_tran_curr_2' => $row[14],
            'currency_2' => $row[15],
            'value_tran_curr_3' => $row[16],
            'currency_3' => $row[17],
            'document_date' => \Carbon\Carbon::createFromFormat('m/d/Y', $row[18])->format('Y-m-d'),
            'posting_date' => \Carbon\Carbon::createFromFormat('m/d/Y', $row[19])->format('Y-m-d'),
            'purchasing_document' => $row[20],
            'supplier' => $row[21],
            'name_1' => $row[22],
            'asset' => $row[23],
            'sub_number' => $row[24],
            'cost_center' => $row[25],
            'gl_account' => $row[26],
            'document_number_2' => $row[27],
            'company_code_2' => $row[28],
            'fiscal_year_2' => $row[29],
            'document_date_2' => \Carbon\Carbon::createFromFormat('m/d/Y', $row[30])->format('Y-m-d'),
            'posting_date_2' => \Carbon\Carbon::createFromFormat('m/d/Y', $row[31])->format('Y-m-d'),
            'user_name' => $row[32],
            'reversed_with' => $row[33],
            'wbs_level_2' => $row[34],
            'wbs_element_2' => $row[35],
        ]);
    }
}

