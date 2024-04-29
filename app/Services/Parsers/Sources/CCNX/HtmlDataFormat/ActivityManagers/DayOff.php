<?php

namespace App\Services\Parsers\Sources\CCNX\HtmlDataFormat\ActivityManagers;

use App\Contracts\ActivityManager;
use App\Models\ValueObjects\ActivityType;
use DOMXPath;

class DayOff extends \App\Services\Parsers\Sources\CCNX\HtmlDataFormat\ActivityManagers\ActivityManager implements ActivityManager
{
    protected function getType()
    {
        return ActivityType::DAY_OFF;
    }

    protected function getFlightNumber(DOMXPath $domXPath, $activityRowData)
    {
        //we don't have flight number in such activity
        return '';
    }
}
