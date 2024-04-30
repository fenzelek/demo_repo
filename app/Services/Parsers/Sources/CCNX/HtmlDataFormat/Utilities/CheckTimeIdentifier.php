<?php

namespace App\Services\Parsers\Sources\CCNX\HtmlDataFormat\Utilities;

use Carbon\Carbon;
use DOMXPath;

class CheckTimeIdentifier
{

    public function getCheckIn(DOMXPath $domXPath, $row, $activityDate): ?Carbon
    {
        $timeStartClass = 'activitytablerow-checkinutc';

        $xpathExpression = ".//td[contains(@class, '$timeStartClass')]";

        return $this->executeQuery($domXPath, $xpathExpression, $row, $activityDate);
    }

    public function getCheckOut(DOMXPath $domXPath, $row, $activityDate): ?Carbon
    {
        $timeStartClass = 'activitytablerow-checkoututc';

        $xpathExpression = ".//td[contains(@class, '$timeStartClass')]";

        return $this->executeQuery($domXPath, $xpathExpression, $row, $activityDate);
    }

    private function executeQuery(DOMXPath $domXPath ,$xpathExpression, $row, $activityDate)
    {
        $targetColumn = $domXPath->query($xpathExpression, $row)->item(0);

        if ($targetColumn) {
            //we need to merge the date from activity date (which is defined outside) and time from current row data
            $time = $targetColumn->textContent;

            //sometimes in time we have a white chars - means, there is no value
            if(strlen($time) < 4){
                return null;
            }

            $dateTimeString = $activityDate->format('Y-m-d') . ' ' . substr($time, 0, 2) . ':' . substr($time, 2, 2);
            return Carbon::createFromFormat('Y-m-d H:i', $dateTimeString);
        }

        return null;
    }
}
