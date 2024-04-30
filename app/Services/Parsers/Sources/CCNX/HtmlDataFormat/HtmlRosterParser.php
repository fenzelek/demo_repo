<?php

namespace App\Services\Parsers\Sources\CCNX\HtmlDataFormat;

use App\Contracts\ParserService;
use App\Services\Parsers\Sources\CCNX\HtmlDataFormat\Utilities\ActivityManagerFactory;
use App\Services\Parsers\Sources\CCNX\HtmlDataFormat\Utilities\ActivityTypeIdentifier;
use App\Services\Parsers\Sources\CCNX\HtmlDataFormat\Utilities\CheckTimeIdentifier;
use App\Services\Parsers\Sources\CCNX\HtmlDataFormat\Utilities\DateOperator;
use DOMDocument;
use DOMXPath;

use Illuminate\Http\UploadedFile;

/**
 * This is a main service for CCNX->Html, that is a facade patter class
 * Main responsibility is to iterate over activity rows from provided html content
 * and fire data parsing by proper activity type managers
 */
class HtmlRosterParser implements ParserService
{
    protected $htmlContent;
    protected $domDocument;
    protected $domXPath;

    protected $factory;
    private DateOperator $dateOperator;
    private ActivityTypeIdentifier $typeIdentifier;
    private CheckTimeIdentifier $timeIdentifier;

    public function __construct(ActivityManagerFactory $factory, DateOperator $dateOperator, ActivityTypeIdentifier $typeIdentifier, CheckTimeIdentifier $timeIdentifier)
    {
        $this->factory = $factory;
        $this->dateOperator = $dateOperator;
        $this->typeIdentifier = $typeIdentifier;
        $this->timeIdentifier = $timeIdentifier;
    }

    public function parse(UploadedFile $content)
    {

        libxml_use_internal_errors(true);

        $this->domDocument = new DOMDocument();

        $this->domDocument->loadHTMLFile($content);
        $this->domXPath = new DOMXPath($this->domDocument);

        $startDate = $this->dateOperator->getPeriodStartDate($this->domXPath);

        $activityRows = $this->domXPath->query('//table[@id="ctl00_Main_activityGrid"]//tr[not(@class="activity-table-header")]');
        $dateIterator = 0;

        /*
         * initialize activity date for the first time
         * on that stage it has to be the same as report start date
         */
        $activityDate = $startDate;

        foreach ($activityRows as $row) {

            //in some cases the record keep data for two activities - e.g for flight and for check in or check out
            //that one check for Flight, Standby, DayOff
            $activity = $this->typeIdentifier->getType($this->domXPath, $row);

            //that one check if this is also check in OR check out activity


            /* determine the activity date
             * we need to do it here, as we don't have enough data in rows directly to figure out the date
             * so we need to monitor the changes in rows and based on that compare with report start date and determine
             * date for current row
             */
            if( $this->dateOperator->isNextDay($this->domXPath, $startDate,$dateIterator, $row ) ) {
                $dateIterator++;
                $activityDate->modify("+1 days");
            }

            $checkInTime = $this->timeIdentifier->getCheckIn($this->domXPath, $row, $activityDate);
            $checkOutTime = $this->timeIdentifier->getCheckOut($this->domXPath, $row, $activityDate);

            $activityManager = $this->factory->create($activity, $checkInTime, $checkOutTime);
            $activityManager->processActivity($this->domXPath, $row, $activityDate);
        }

    }

}
