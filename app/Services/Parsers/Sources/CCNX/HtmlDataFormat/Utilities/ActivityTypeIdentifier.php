<?php

namespace App\Services\Parsers\Sources\CCNX\HtmlDataFormat\Utilities;

use Carbon\Carbon;
use DOMXPath;

class ActivityTypeIdentifier
{
    public function getType(DOMXPath $domXPath, $row) {
        $tdActivityClass = 'activitytablerow-activity';

        $xpathExpression = ".//td[contains(@class, '$tdActivityClass')]";

        $targetColumn = $domXPath->query($xpathExpression, $row)->item(0);

        if ($targetColumn) {
            return $targetColumn->textContent;
        }

        // in case if now data found
        //TODO that should be improved to some better solution instead of hardcoded text
        return 'NO DATA FOUND';
    }

    public function getCheckIn(DOMXPath $domXPath, $row, $activityDate) {
        $timeStartClass = 'activitytablerow-checkinutc';

        $xpathExpression = ".//td[contains(@class, '$timeStartClass')]";

        $targetColumn = $domXPath->query($xpathExpression, $row)->item(0);

        if ($targetColumn) {
            //we need to merge the date from activity date (which is defined outside) and time from current row data
            $time = $targetColumn->textContent;
            $dateTimeString = $activityDate->format('Y-m-d') . ' ' . substr($time, 0, 2) . ':' . substr($time, 2, 2);
            return Carbon::createFromFormat('Y-m-d H:i', $dateTimeString);
        }
    }
}
