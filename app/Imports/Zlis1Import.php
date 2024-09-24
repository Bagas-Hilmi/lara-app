<?php

namespace App\Imports;

use App\Models\Zlis1Tail;
use Maatwebsite\Excel\Concerns\ToModel;

class Zlis1Import implements ToModel
{
    public function model(array $row)
    {
        return new Zlis1Tail([
            'WBS Element' => $row[0],  // WBS Element
            'Network' => $row[1],  // Network
            'Document Number' => $row[2],  // Document Number
            'Company Code' => $row[3],  // Company Code
            'Fiscal Year' => $row[4],  // Fiscal Year
            'Item' => $row[5],  // Item
            'Material Document' => $row[6],  // Material Document
            'Material Doc. Year' => $row[7],  // Material Doc. Year
            'Material' => $row[8],  // Material
            'Description' => $row[9],  // Description
            'Quantity' => $row[10],  // Quantity
            'Base Unit of Measure' => $row[11],  // Base Unit of Measure
            'Value TranCurr' => $row[12],  // Value TranCurr
            'Currency' => $row[13],  // Currency
            'Document Date' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[14]),  // Document Date
            'Posting Date' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[15]),  // Posting Date
            'Purchasing Document' => $row[16],  // Purchasing Document
            'Supplier' => $row[17],  // Supplier
            'Name 1' => $row[18],  // Name 1
            'Asset' => $row[19],  // Asset
            'Sub-number' => $row[20],  // Sub-number
            'Cost Center' => $row[21],  // Cost Center
            'G/L Account' => $row[22],  // G/L Account
            'User Name' => $row[23],  // User Name
            'Reversed with' => $row[24],  // Reversed with
            'WBS level 2' => $row[25],  // WBS level 2
            'WBS Element' => $row[26],  // WBS Element
            'period' => $row[27],  // Period dari form input
        ]);
    }
}

