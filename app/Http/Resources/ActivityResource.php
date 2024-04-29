<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ActivityResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'type' => $this->Type,
            'from' => $this->From,
            'to' => $this->To,
            'start' => Carbon::parse($this->Start)->format('H:i'),
            'end' => Carbon::parse($this->End)->format('H:i'),
            'date' => Carbon::parse($this->Date)->format('Y/m/d'),
        ];
    }
}
