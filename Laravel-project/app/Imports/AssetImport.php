<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\ListAsset;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AssetImport implements ToModel, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    private $id_capex;

    public function __construct($id_capex)
    {
        $this->id_capex = $id_capex;
    }
    public function model(array $row)
    {
        return new ListAsset([
            'id_capex' => $this->id_capex,
            'no' => $row['No'],
            'cost_center' => $row['Cost Center'],
            'tag_number' => $row['TAG Number'],
            'asset_number' => $row['Asset No.'],
            'asset_number_2' => $row['Asset No'],
            'asset_class_id' => $row['Asset Class ID'],
            'asset_class_name' => $row['Asset Class name'],
            'life_k' => $row[7],
            'life_k' => $row[8],
            'asset_name' => $row[9],
            'plant' => $row[10],
            'qty' => $row[11],
            'uom' => $row[12],
        ]);
    }
}
