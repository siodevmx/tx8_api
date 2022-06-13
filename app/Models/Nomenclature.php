<?php

namespace App\Models;

use App\Traits\UUIDTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nomenclature extends Model
{
    use HasFactory, UUIDTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'show'
    ];

    protected $casts = [
        'show' => 'boolean',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class)
            ->withPivot('id', 'nomenclature_value', 'has_time', 'price', 'compare_at_price', 'inventory_cost', 'sku', 'stock', 'thumbnail_path');
    }

}
