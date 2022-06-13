<?php

namespace App\Http\Resources\HourPrice;

use Illuminate\Http\Resources\Json\JsonResource;

class HourPriceResource extends JsonResource
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
            'id' => $this->id,
            'start_at' => $this->start_at,
            'finish_at' => $this->finish_at,
            'price' => $this->price,
            'compare_at_price' => $this->compare_at_price,
        ];
    }
}
