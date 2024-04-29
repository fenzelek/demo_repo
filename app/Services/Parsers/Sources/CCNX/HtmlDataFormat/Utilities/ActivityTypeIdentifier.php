<?php

namespace App\Services\Parsers\Sources\CCNX\HtmlDataFormat\Utilities;

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
}
