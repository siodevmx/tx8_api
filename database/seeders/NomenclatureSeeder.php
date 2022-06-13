<?php

namespace Database\Seeders;

use App\Models\Nomenclature;
use Illuminate\Database\Seeder;

class NomenclatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Nomenclature::create(
            [
                'name' => 'default',
                'show' => 0
            ]);
        Nomenclature::create(['name' => 'LT']);
        Nomenclature::create(['name' => 'ML']);
        Nomenclature::create(['name' => 'GR']);
        Nomenclature::create(['name' => 'OZ']);
        Nomenclature::create(['name' => 'LB']);
        Nomenclature::create(['name' => 'KG']);

    }
}
