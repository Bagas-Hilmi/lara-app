<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\ListAsset;
use Maatwebsite\Excel\Concerns\ToModel;


class AssetImport implements ToModel
{
    /**
    * @param Collection $collection
    */
    public function model(array $row)
    {
        return new ListAsset([
            'no' => $row[0],
            'cost_center' => $row[1],
            'tag_number' => $row[2],
            'asset_number' => $row[3],
            'asset_number_2' => $row[4],
            'asset_class_id' => $row[5],
            'asset_class_name' => $row[6],
            'life_k' => $row[7],
            'life_k' => $row[8],
            'asset_name' => $row[9],
            'plant' => $row[10],
            'qty' => $row[11],
            'uom' => $row[12],
        ]);
    }
}
