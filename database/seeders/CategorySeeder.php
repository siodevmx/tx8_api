<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::create(['name' => 'Botanas', 'description' => 'Botanas de todo tipo']);
        Category::create(['name' => 'Vinos', 'description' => 'Vinos de todo tipo']);
        Category::create(['name' => 'Bebidas', 'description' => 'Bebidas de todo tipo']);
        Category::create(['name' => 'Licores', 'description' => 'Licores de todo tipo']);
        Category::create(['name' => 'Destilados', 'description' => 'Destilados de todo tipo']);
        Category::create(['name' => 'Alimentos', 'description' => 'Alimentos de todo tipo']);
        Category::create(['name' => 'Cervezas', 'description' => 'Cervezas de todo tipo']);
        Category::create(['name' => 'Gourmet', 'description' => 'Gourmet de todo tipo']);
        Category::create(['name' => 'Whisky', 'description' => 'Whisky de todo tipo']);
    }
}
