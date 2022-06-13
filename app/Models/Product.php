<?php

namespace App\Models;

use App\Traits\UUIDTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

class Product extends Model
{
    use HasFactory, UUIDTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'category_id',
        'status',
        'type',
        'slug'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function nomenclatures()
    {
        return $this->belongsToMany(Nomenclature::class)->withPivot('id', 'nomenclature_value', 'has_time', 'price', 'compare_at_price', 'inventory_cost', 'sku', 'stock', 'thumbnail_path');
    }


    /**
     * @param string $name
     * @param mixed $query
     *
     * @return Builder
     */
    public function scopeName($query, string $name): Builder
    {
        return $query->where('name', 'LIKE', "%{$name}%");
    }
}
