<?php

namespace App\Contracts;

use DateTime;
use DOMXPath;

interface ActivityManager
{
    public function processActivity(DOMXPath $domXPath, $activityRowData, DateTime $activityDate):void;
}
