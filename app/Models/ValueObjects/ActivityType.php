<?php

namespace App\Models\ValueObjects;


use Illuminate\Validation\Rules\Enum;

//represents unified activity symbols to be stored in our system
enum ActivityType:string
{
    case DAY_OFF = 'DO';
    case STANDBY = 'SBY';
    case FLIGHT = 'FLT';
    case CHECK_IN = 'CI';
    case CHECK_OUT = 'CO';
    case UNKNOWN = 'UNK';

}
