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
        $item = '';
        if($this->inventoryable->item){
            $item = $this->inventoryable->item;
        }
        if($this->inventoryable->code){
            $item = $this->inventoryable->code;
        }
        if($this->inventoryable->type){
            $item = $this->inventoryable->type;
        }
        return [
            'amount' => $this->amount,
            'inventoryable' => $item,
        ];
    }
}
