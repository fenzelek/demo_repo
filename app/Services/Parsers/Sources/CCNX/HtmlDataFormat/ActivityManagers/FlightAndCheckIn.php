<?php

namespace App\Services\Parsers\Sources\CCNX\HtmlDataFormat\ActivityManagers;

use App\Contracts\ActivityManager as ActivityManagerInterface;
use App\Events\ActivityProcessed;
use App\Models\ValueObjects\ActivityType;
use App\Services\Parsers\Sources\CCNX\HtmlDataFormat\DTO\ParsedActivityData;
use App\Services\Parsers\Sources\CCNX\HtmlDataFormat\Utilities\CheckTimeIdentifier;
use DateTime;
use DOMXPath;
use Illuminate\Contracts\Events\Dispatcher as EventDispatcherInterface;
use Illuminate\Contracts\Foundation\Application;

class FlightAndCheckIn extends Flight implements ActivityManagerInterface
{
    private CheckTimeIdentifier $timeIdentifier;

    public function __construct(EventDispatcherInterface $eventDispatcher, Application $app, CheckTimeIdentifier $timeIdentifier)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->app = $app;
        $this->timeIdentifier = $timeIdentifier;
    }

    public function processActivity(DOMXPath $domXPath, $activityRowData, DateTime $activityDate): void
    {
        parent::processActivity($domXPath, $activityRowData, $activityDate);

        //within that case we have to fire two activities - flight and check in
        $checkInParsedData = $this->createCheckInData($domXPath, $activityRowData, $activityDate,ActivityType::CHECK_IN );

        $this->lastEvent = new ActivityProcessed($checkInParsedData);
        $this->eventDispatcher->dispatch($this->lastEvent );

    }

    protected function createCheckInData(DOMXPath $domXPath, $activityRowData, DateTime $activityDate, $type)
    {
        $from = $this->getClassData($domXPath, $activityRowData, $this->fromClass);
        $start = $this->timeIdentifier->getCheckIn($domXPath, $activityRowData, $activityDate);

        return  $this->app->make(ParsedActivityData::class,
            [
                'flightNumber' => $this->getFlightNumber($domXPath, $activityRowData),
                'type' => $type,
                'date' => $activityDate,
                'from' => $from,
                'to' => "",
                'start' => $start,
                'end' => null,
            ]);
    }
}
