<?php
namespace Database\Seeders;

use App\Models\AssetCategory;
use App\Models\FabricCategory;
use Illuminate\Database\Seeder;
use App\Models\MaterialCategory;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        FabricCategory::updateOrCreate([
            'location_id'   => 1,
            'name' => "Knit",
        ],[
            'location_id'   => 1,
            'name' => "Knit",
        ]);

        FabricCategory::updateOrCreate([
            'location_id'   => 2,
            'name' => "Knit",
        ],[
            'location_id'   => 2,
            'name' => "Knit",
        ]);

        FabricCategory::updateOrCreate([
            'location_id'   => 1,
            'name' => "Woven",
        ],[
            'location_id'   => 1,
            'name' => "Woven",
        ]);

        FabricCategory::updateOrCreate([
            'location_id'   => 2,
            'name' => "Woven",
        ],[
            'location_id'   => 2,
            'name' => "Woven",
        ]);

        FabricCategory::updateOrCreate([
            'location_id'   => 1,
            'name' => "Pant",
        ],[
            'location_id'   => 1,
            'name' => "Pant",
        ]);

        FabricCategory::updateOrCreate([
            'location_id'   => 2,
            'name' => "Pant",
        ],[
            'location_id'   => 2,
            'name' => "Pant",
        ]);

        //Material Catgory
        MaterialCategory::updateOrCreate([
            'location_id'   => 1,
            'name' => "Electrical",
        ],[
            'location_id'   => 1,
            'name' => "Electrical",
        ]);

        MaterialCategory::updateOrCreate([
            'location_id'   => 2,
            'name' => "Electrical",
        ],[
            'location_id'   => 2,
            'name' => "Electrical",
        ]);

        MaterialCategory::updateOrCreate([
            'location_id'   => 1,
            'name' => "Garments",
        ],[
            'location_id'   => 1,
            'name' => "Garments",
        ]);

        MaterialCategory::updateOrCreate([
            'location_id'   => 2,
            'name' => "Garments",
        ],[
            'location_id'   => 2,
            'name' => "Garments",
        ]);

        MaterialCategory::updateOrCreate([
            'location_id'   => 1,
            'name' => "Accessories",
        ],[
            'location_id'   => 1,
            'name' => "Accessories",
        ]);

        MaterialCategory::updateOrCreate([
            'location_id'   => 2,
            'name' => "Accessories",
        ],[
            'location_id'   => 2,
            'name' => "Accessories",
        ]);

        MaterialCategory::updateOrCreate([
            'location_id'   => 1,
            'name' => "Others",
        ],[
            'location_id'   => 1,
            'name' => "Others",
        ]);

        MaterialCategory::updateOrCreate([
            'location_id'   => 2,
            'name' => "Others",
        ],[
            'location_id'   => 2,
            'name' => "Others",
        ]);

        //Asset Category
        AssetCategory::updateOrCreate([
            'location_id'   => 1,
            'name' => "Accessories",
        ],[
            'location_id'   => 1,
            'name' => "Accessories",
        ]);

        AssetCategory::updateOrCreate([
            'location_id'   => 2,
            'name' => "Accessories",
        ],[
            'location_id'   => 2,
            'name' => "Accessories",
        ]);

        AssetCategory::updateOrCreate([
            'location_id'   => 1,
            'name' => "Stationary",
        ],[
            'location_id'   => 1,
            'name' => "Stationary",
        ]);

        AssetCategory::updateOrCreate([
            'location_id'   => 2,
            'name' => "Stationary",
        ],[
            'location_id'   => 2,
            'name' => "Stationary",
        ]);

        AssetCategory::updateOrCreate([
            'location_id'   => 1,
            'name' => "Electrical",
        ],[
            'location_id'   => 1,
            'name' => "Electrical",
        ]);

        AssetCategory::updateOrCreate([
            'location_id'   => 2,
            'name' => "Electrical",
        ],[
            'location_id'   => 2,
            'name' => "Electrical",
        ]);

        AssetCategory::updateOrCreate([
            'location_id'   => 1,
            'name' => "Promotional",
        ],[
            'location_id'   => 1,
            'name' => "Promotional",
        ]);

        AssetCategory::updateOrCreate([
            'location_id'   => 2,
            'name' => "Promotional",
        ],[
            'location_id'   => 2,
            'name' => "Promotional",
        ]);

        AssetCategory::updateOrCreate([
            'location_id'   => 1,
            'name' => "Others",
        ],[
            'location_id'   => 1,
            'name' => "Others",
        ]);

        AssetCategory::updateOrCreate([
            'location_id'   => 2,
            'name' => "Others",
        ],[
            'location_id'   => 2,
            'name' => "Others",
        ]);

    }
}
