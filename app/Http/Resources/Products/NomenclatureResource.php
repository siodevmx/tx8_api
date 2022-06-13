<?php

namespace App\Http\Resources\Products;

use App\Http\Resources\HourPrice\HourPriceResource;
use App\Models\HourPrice;
use App\Models\NomenclatureProduct;
use Illuminate\Http\Resources\Json\JsonResource;

class NomenclatureResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'variant_id' => $this->whenPivotLoaded('nomenclature_product', function () {
                return $this->pivot->id;
            }),
            'nomenclature_id' => $this->id,
            'name' => $this->name,
            'nomenclature_value' => $this->whenPivotLoaded('nomenclature_product', function () {
                return $this->pivot->nomenclature_value;
            }),
            'has_time' => $this->whenPivotLoaded('nomenclature_product', function () {
                return $this->pivot->has_time;
            }),
            'price' => $this->whenPivotLoaded('nomenclature_product', function () {
                return $this->pivot->price;
            }),
            'compare_at_price' => $this->whenPivotLoaded('nomenclature_product', function () {
                return $this->pivot->compare_at_price;
            }),
            'inventory_cost' => $this->whenPivotLoaded('nomenclature_product', function () {
                return $this->pivot->inventory_cost;
            }),
            'sku' => $this->whenPivotLoaded('nomenclature_product', function () {
                return $this->pivot->sku;
            }),
            'stock' => $this->whenPivotLoaded('nomenclature_product', function () {
                return $this->pivot->stock;
            }),
            'thumbnail_path' => $this->whenPivotLoaded('nomenclature_product', function () {
                return $this->pivot->thumbnail_path;
            }),
//            'hours_price' => $this->whenPivotLoaded('nomenclature_product', function () {
//                return $this->pivot->hours_price;
//            }),
            'hours_price' => $this->whenPivotLoaded('nomenclature_product', function () {
                $hours = HourPrice::where('nomenclature_product_id', $this->pivot->id)->get();
                return HourPriceResource::collection($hours);
            })
        ];
    }
}
