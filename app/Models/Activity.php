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

    public function getStartAttribute($value)
    {
        return Carbon::parse($value);
    }

    public function getEndAttribute($value)
    {
        return Carbon::parse($value);
    }
}
