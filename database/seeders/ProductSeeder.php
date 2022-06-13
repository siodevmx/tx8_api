<?php

namespace Database\Seeders;

use App\Models\NomenclatureProduct;
use App\Models\Product;
use App\Models\ProductNomenclature;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $product1 = new Product();
        $product1->name = 'Whiskey Jack Daniel´s Tennessee';
        $product1->description = 'Botella Whhiskey';
        $product1->type = 'variant';
        $product1->save();

        $product1Data1 = [
            'nomenclature_id' => 2,
            'nomenclature_value' => '350',
            'price' => 240.87,
            'compare_at_price' => 00.00,
            'inventory_cost' => 220.56,
            'sku' => '10000006594',
            'stock' => 2,
            'thumbnail_path' => 'https://i.ibb.co/rG292fv/WHISKY-JACK-DANIELS-350-ML.png'
        ];
        $product1Data2 = [
            'nomenclature_id' => 2,
            'nomenclature_value' => '700',
            'price' => 429.47,
            'compare_at_price' => 00.00,
            'inventory_cost' => 410.00,
            'sku' => '10000002633',
            'stock' => 5,
            'thumbnail_path' => 'https://www.laeuropea.com.mx/media/catalog/product/cache/97ac165c6b6dd76d4f7d63f65cdf4c94/1/0/10000002633_1.jpg'
        ];
        $product1Data3 = [
            'nomenclature_id' => 1,
            'nomenclature_value' => '1',
            'price' => 574.00,
            'compare_at_price' => 00.00,
            'inventory_cost' => 524.00,
            'sku' => '10000000636',
            'stock' => 8,
            'thumbnail_path' => 'https://www.laeuropea.com.mx/media/catalog/product/cache/97ac165c6b6dd76d4f7d63f65cdf4c94/1/6/1603_1.jpg'
        ];
        $product1->nomenclatures()->syncWithoutDetaching([$product1->id => $product1Data1]);
        $product1->nomenclatures()->syncWithoutDetaching([$product1->id => $product1Data2]);
        $product1->nomenclatures()->syncWithoutDetaching([$product1->id => $product1Data3]);


        $product2 = new Product();
        $product2->name = 'Cerveza Corona Extra';
        $product2->description = 'Botella de vidrio';
        $product2->type = 'single';
        $product2->save();

        $product2Data1 = [
            'nomenclature_id' => 2,
            'nomenclature_value' => '355',
            'price' => 16.00,
            'compare_at_price' => 18.20,
            'inventory_cost' => 12.54,
            'sku' => '10000000412',
            'stock' => 34,
            'thumbnail_path' => 'https://www.laeuropea.com.mx/media/catalog/product/cache/97ac165c6b6dd76d4f7d63f65cdf4c94/0/7/07501064101410.1.png'
        ];
        $product2->nomenclatures()->syncWithoutDetaching([$product2->id => $product2Data1]);


        $product3 = new Product();
        $product3->name = 'Vino Tinto Finca Iriarte Tempranillo';
        $product3->description = 'VT FINCA IRIARTE TEMPRANILLO';
        $product3->type = 'single';
        $product3->save();

        $product3Data1 = [
            'nomenclature_id' => 2,
            'nomenclature_value' => '750',
            'price' => 213.20,
            'compare_at_price' => 266.50,
            'inventory_cost' => 198.99,
            'sku' => '10000019379',
            'stock' => 4,
            'thumbnail_path' => 'https://i.ibb.co/c3PsSJv/Vino-Tinto-Finca.png'
        ];
        $product3->nomenclatures()->syncWithoutDetaching([$product3->id => $product3Data1]);


        $product4 = new Product();
        $product4->name = 'Chetos Flamin Hot';
        $product4->description = 'Chetos sabor flamin hot';
        $product4->type = 'variant';
        $product4->save();

        $product4Data1 = [
            'nomenclature_id' => 3,
            'nomenclature_value' => '115',
            'price' => 16.20,
            'compare_at_price' => 18.10,
            'inventory_cost' => 12.00,
            'sku' => '7500478006175',
            'stock' => 6,
            'thumbnail_path' => 'https://i.ibb.co/VWFX15W/0750101113093-L-removebg-preview.png'
        ];
        $product4Data2 = [
            'nomenclature_id' => 3,
            'nomenclature_value' => '42',
            'price' => 11.08,
            'compare_at_price' => 14.10,
            'inventory_cost' => 08.00,
            'sku' => '7501011143753',
            'stock' => 5,
            'thumbnail_path' => 'https://i.ibb.co/VWFX15W/0750101113093-L-removebg-preview.png'
        ];

        $product4->nomenclatures()->syncWithoutDetaching([$product4->id => $product4Data1]);
        $product4->nomenclatures()->syncWithoutDetaching([$product4->id => $product4Data2]);
    }
}
