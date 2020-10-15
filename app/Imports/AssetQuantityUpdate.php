<?php

namespace App\Imports;

use App\Models\Location;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AssetQuantityUpdate implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row)
        {
            Location::where('name', $row['location'])->first()
                    ->assets()->where('code', $row['code'])->first()
                    ->update(['quantity' => $row['quantity']]);
        }
    }
}
