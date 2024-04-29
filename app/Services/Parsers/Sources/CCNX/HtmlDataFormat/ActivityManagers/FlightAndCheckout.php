<?php

namespace App\Services\Parsers\Sources\CCNX\HtmlDataFormat\ActivityManagers;

use App\Contracts\ActivityManager as ActivityManagerInterface;
use App\Models\ValueObjects\ActivityType;

class Flight_AndCheckout extends ActivityManager implements ActivityManagerInterface
{
    protected function getType()
    {
        return ActivityType::FLIGHT;
    }
}
