<?php

namespace App\Services\Parsers\Sources\CCNX\HtmlDataFormat\ActivityManagers;

use App\Contracts\ActivityManager as ActivityManagerInterface;
use App\Models\ValueObjects\ActivityType;
use DOMXPath;

class Flight extends ActivityManager implements ActivityManagerInterface
{
    protected function getType()
    {
        return ActivityType::FLIGHT;
    }

    protected function getFlightNumber(DOMXPath $domXPath, $activityRowData)
    {
        $tdActivityClass = 'activitytablerow-activity';
        return $this->getClassData($domXPath, $activityRowData, $tdActivityClass);
    }
}
