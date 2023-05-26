<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InventoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'warehouse' => $this->warehouse,
            'inventory' => $this->inventory,
            'inventory' => WarehouseInventoryResource::collection($this->inventory),
            'created_at' => $this->created_at,
        ];
    }
}
