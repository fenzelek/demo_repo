<?php

namespace App\Services\Parsers\Sources\CCNX\HtmlDataFormat\Utilities;

use DateTime;
use DOMXPath;

class DateOperator
{
    public function getPeriodStartDate(DOMXPath $domXPath)
    {
        $periodNode = $domXPath->query('//div[@class="row printOnly"]/b[starts-with(., "Period: ")]')->item(0);
        $periodText = $periodNode->nodeValue;

        preg_match('/Period:\s*(\d{1,2}[A-Z][a-z]{2})(\d{2})\s*to\s*(\d{1,2}[A-Z][a-z]{2})(\d{2})/i', $periodText, $matches);
        $startDate = DateTime::createFromFormat('dMy', $matches[1] . $matches[2]);

        /*
         * for now we don't need the end date, but leaving that to remember how to find it
         * to no think about that in the future,as I already discovered that
         */
        //$endDate = DateTime::createFromFormat('dMy', $matches[3] . $matches[4]);


        return $startDate;
    }

    public function isNextDay(DOMXPath $domXPath, DateTime $startDate, $dateIterator, $activityData):bool
    {
        $tdActivityClass = 'activitytablerow-date';
        $xpathExpression = ".//td[contains(@class, '$tdActivityClass')]";

        $dateCell = $domXPath->query($xpathExpression, $activityData)->item(0);

        $dateText = trim($dateCell->nodeValue);
        if (!empty($dateText)) {
            $day = substr($dateText, 4); // np. "10", "11", "12"

            $activityDate = (clone $startDate)->modify("+$dateIterator days");
            $dayOfMonth = $activityDate->format('d');
            return ($dayOfMonth != $day);
        }
        return false;
    }
}
