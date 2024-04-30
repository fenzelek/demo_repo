<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    public function getDateAttribute($value)
    {
        return Carbon::parse($value);
    }

    public function getStartAttribute($value):?Carbon
    {
        if($value)
            return Carbon::parse($value);

        return null;
    }

    public function getEndAttribute($value):?Carbon
    {
        if($value)
            return Carbon::parse($value);

        return null;
    }
}
