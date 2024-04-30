<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ActivityResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'flight_number' => $this->FlightNumber,
            'type' => $this->Type,
            'from' => $this->From,
            'to' => $this->To,
            'start' => $this->Start ? Carbon::parse($this->Start)->format('H:i') : "",
            'end' => $this->End ? Carbon::parse($this->End)->format('H:i') : "",
            'date' => Carbon::parse($this->Date)->format('Y/m/d'),
        ];
    }
}
