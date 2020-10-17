<?php

namespace App\Imports;

use App\Models\Location;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class AssetQuantityUpdate implements ToCollection, WithHeadingRow, WithValidation
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
                    ->update(
                        [
                            'opening_quantity' => $row['quantity'],
                            'quantity' => $row['quantity'],
                        ]
                    );
        }
    }

    public function rules(): array
    {
        return [
             'quantity' => 'required|numeric',
        ];
    }
}
