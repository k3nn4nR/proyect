<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class TotalDayPayments extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'code' => ($this->currency->codes->isNotEmpty())? $this->currency->codes->first()->code : '',
            'company' => $this->company->company,
            'currency' => $this->currency->currency,
            'total' => $this->total,
            'created_at' => Carbon::create($this->created_at)->toDateTimeString(),
        ];
    }
}
