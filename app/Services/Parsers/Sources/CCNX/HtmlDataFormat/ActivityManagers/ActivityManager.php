<?php

namespace App\Services\Parsers\Sources\CCNX\HtmlDataFormat\ActivityManagers;

use App\Contracts\ActivityManager as ActivityInterface;
use App\Events\ActivityProcessed;
use App\Services\Parsers\Sources\CCNX\HtmlDataFormat\DTO\ParsedActivityData;
use Carbon\Carbon;
use DateTime;
use DOMXPath;
use Illuminate\Contracts\Events\Dispatcher as EventDispatcherInterface;
use Illuminate\Contracts\Foundation\Application;


abstract class ActivityManager implements ActivityInterface
{
    protected $fromClass = 'activitytablerow-fromstn';
    protected $toClass = 'activitytablerow-tostn';
    protected $timeStartClass = 'activitytablerow-stdutc';
    protected $timeEndClass = 'activitytablerow-stautc';

    protected EventDispatcherInterface $eventDispatcher;
    protected Application $app;
    protected $lastEvent = null;

    protected abstract function getType();
    protected abstract function getFlightNumber(DOMXPath $domXPath, $activityRowData);

    public function __construct(EventDispatcherInterface $eventDispatcher, Application $app)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->app = $app;
    }

    public function processActivity(DOMXPath $domXPath, $activityRowData, DateTime $activityDate): void
    {
        //create the "domain" or should say "business" object to keep the data and provide outside of the current context logic
        $activityParsedData = $this->createParsedData($domXPath, $activityRowData, $activityDate, $this->getType() );

        $this->lastEvent = new ActivityProcessed($activityParsedData);
        $this->eventDispatcher->dispatch($this->lastEvent);

    }

    protected function createParsedData(DOMXPath $domXPath, $activityRowData, DateTime $activityDate, $type)
    {
        $from = $this->getClassData($domXPath, $activityRowData, $this->fromClass);
        $to = $this->getClassData($domXPath, $activityRowData, $this->toClass);
        $start = $this->getTime($domXPath, $activityRowData, $this->timeStartClass, $activityDate);
        $end = $this->getTime($domXPath, $activityRowData, $this->timeEndClass, $activityDate);

        return  $this->app->make(ParsedActivityData::class,
            [
                'flightNumber' => $this->getFlightNumber($domXPath, $activityRowData),
                'type' => $type,
                'date' => $activityDate,
                'from' => $from,
                'to' => $to,
                'start' => $start,
                'end' => $end,
            ]);
    }

    protected function getClassData(DOMXPath $domXPath, $activityData, $tdActivityClass):string
    {
        $xpathExpression = ".//td[contains(@class, '$tdActivityClass')]";

        $targetColumn = $domXPath->query($xpathExpression, $activityData)->item(0);

        if ($targetColumn) {
            return $targetColumn->textContent;
        }

        // in case if now data found
        return '';
    }

    protected function getTime(DOMXPath $domXPath, $activityData, $timeClass,DateTime $activityDate): ?Carbon
    {
        $xpathExpression = ".//td[contains(@class, '$timeClass')]";

        $targetColumn = $domXPath->query($xpathExpression, $activityData)->item(0);

        if ($targetColumn) {
            //we need to merge the date from activity date (which is defined outside) and time from current row data
            $time = $targetColumn->textContent;
            $dateTimeString = $activityDate->format('Y-m-d') . ' ' . substr($time, 0, 2) . ':' . substr($time, 2, 2);
            return Carbon::createFromFormat('Y-m-d H:i', $dateTimeString);
        }

        // in case if no data found
        //TODO need to think what to do in that case
        return Carbon::create(1900, 1, 1, 0, 0, 0);
    }

    public function getLastEvent(): ?ActivityProcessed
    {
        return $this->lastEvent;
    }

}
