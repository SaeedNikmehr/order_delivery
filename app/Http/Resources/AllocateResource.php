<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AllocateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'delay_queue_id' => $this->id,
            'order_info' => OrderResource::make($this->order),
            'created_at' => formatDate($this->created_at),
        ];
    }
}
