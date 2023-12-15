<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'vendor_info' => VendorResource::make($this->vendor),
            'user_info' => UserResource::make($this->user),
            'status' => EnumResource::make($this->status),
            'created_at' => formatDate($this->created_at),
        ];
    }
}
