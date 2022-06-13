<?php

namespace App\Models;

use App\Traits\UUIDTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NomenclatureProduct extends Model
{
    use HasFactory,UUIDTrait;

    public $table = 'nomenclature_product';
    public $timestamps = false;

    protected $casts = [
        'price' => 'double',
        'compare_at_price' => 'double'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_id',
        'nomenclature_id',
        'nomenclature_value',
        'has_time',
        'price',
        'compare_at_price',
        'inventory_cost',
        'sku',
        'barcode',
        'thumbnail_path',
        'stock'
    ];



    public function hourPrices()
    {
        return $this->hasMany(HourPrice::class);
    }

}
