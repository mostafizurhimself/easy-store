<?php

namespace Database\Seeders;

use App\Models\Section;
use Illuminate\Database\Seeder;

class SectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Section::updateOrCreate(
            [
                'location_id' => 1,
                'name' => '5th Floor Sewing Section'
            ],
            []
        );
    }
}
