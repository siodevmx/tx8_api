<?php

namespace App\Http\Resources\Nomenclature;

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
            'id' => $this->id,
            'show' => $this->show,
            'name' => $this->name
        ];
    }
}
