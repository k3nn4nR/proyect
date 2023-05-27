<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WarehouseInventoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'amount' => $this->amount,
            'item' => ($this->inventoryable->code)? $this->inventoryable->codeable->type : (($this->inventoryable->type)? $this->inventoryable->type : $this->inventoryable->item),
            'code' => ($this->inventoryable->code)? $this->inventoryable->code : '',
            'tags' => ($this->inventoryable->tags->isEmpty())? [] : WarehouseInventoryTagsResource::collection($this->inventoryable->tags),
        ];
    }
}
