<?php

namespace App\Http\Resources\Products;

use App\Http\Resources\Category\CategoryResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'id'=>$this->id,
            'name' => $this->name,
            'created_at'=>$this->created_at,
            'description' => $this->description,
            'category_id' => $this->category_id,
//            'category' => CategoryResource::make($this->whenLoaded('category')),
            'status' => $this->status,
            'type' => $this->type,
            'slug' => $this->slug,
            'variants' => NomenclatureResource::collection($this->whenLoaded('nomenclatures'))
        ];
    }
}
