<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class CodeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'code' => $this->code,
            'item' => ($this->codeable->type) ? $this->codeable->type : (($this->codeable->brand) ? $this->codeable->brand : (($this->codeable->currency) ? $this->codeable->currency : $this->codeable->item)) ,
            'type' => ($this->codeable->type) ? 'TYPE' : (($this->codeable->brand) ?'BRAND' : (($this->codeable->currency) ? 'CURRENCY' :'ITEM')) ,
            'created_at' => Carbon::create($this->created_at)->toDateTimeString(),
        ];
    }
}
