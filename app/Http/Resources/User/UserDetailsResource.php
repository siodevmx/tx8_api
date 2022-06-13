<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;

class UserDetailsResource extends JsonResource
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
            'user_details_id' => $this->id,
            'name' => $this->name,
            'surnames' => $this->surnames,
            'phone' => $this->phone,
            'identification_verified' => $this->identification_verified,
            'identification' => $this->identification,
            'status' => $this->status
        ];
    }
}
