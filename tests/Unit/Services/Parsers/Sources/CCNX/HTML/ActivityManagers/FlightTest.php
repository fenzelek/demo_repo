<?php

namespace Services\Parsers\Sources\CCNX\HTML\ActivityManagers;

use App\Contracts\ActivityData;
use App\Events\ActivityProcessed;
use App\Events\Listeners\ActivityProcessedListener;
use App\Services\Parsers\Sources\CCNX\HtmlDataFormat\ActivityManagers\Flight;
use DateTime;
use DOMDocument;
use DOMXPath;
use Illuminate\Contracts\Events\Dispatcher;
use Mockery;
use Tests\TestCase;

class FlightTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @Feature parse roster data
     * @scenario html document
     * @case Flight activities
     * @expectation Flight activity data parsed and sent to the event,
     * that will decide what to do with that data
     * @test
     */
    public function it_processes_flight_activity()
    {
        // GIVEN
        $domDocument = new DOMDocument();
        $domDocument->loadHTML($this->getHtml());
        $domXPath = new DOMXPath($domDocument);
        $eventDispatcher = Mockery::mock(Dispatcher::class);
        $app = $this->app;

        $eventDispatcher->shouldReceive('dispatch')->once()->with(ActivityProcessed::class);
        $activityDate = DateTime::createFromFormat('dMY', '10Jan2022');
        // WHEN
        $flightManager = new Flight($eventDispatcher, $app);
        $flightManager->processActivity($domXPath, $domDocument->documentElement, $activityDate);

        // THEN
        $this->assertNotNull($flightManager->getLastEvent());
        $this->assertInstanceOf(ActivityProcessed::class, $flightManager->getLastEvent());
    }

    /**
     * @Feature parse roster data
     * @scenario html document
     * @case Flight activities
     * @expectation check if the start & end time is parsed properly
     * @test
     */
    public function it_processes_flight_time()
    {
        // GIVEN
        $domDocument = new DOMDocument();
        $domDocument->loadHTML($this->getHtml());
        $domXPath = new DOMXPath($domDocument);

        $listenerMock = Mockery::mock(ActivityProcessedListener::class);
        $listenerMock->shouldReceive('handle')->once();

        $this->app->instance(ActivityProcessedListener::class, $listenerMock);

        $app = $this->app;

        $activityDate = DateTime::createFromFormat('dMY', '10Jan2022');

        // WHEN
        $flightManager = $this->app->make(Flight::Class);
        $flightManager->processActivity($domXPath, $domDocument->documentElement, $activityDate);

        // THEN
        $parsedData = $flightManager->getLastEvent()->getActivityData();
        $this->assertInstanceOf(ActivityData::class, $parsedData);

        $start = $parsedData->getStart();
        $end = $parsedData->getEnd();

        $this->assertEquals("10 January 2022 13:45", $start->format('d F Y H:i'));
        $this->assertEquals("10 January 2022 14:35", $end->format('d F Y H:i'));

    }

    /**
     * @Feature parse roster data
     * @scenario html document
     * @case Flight activities
     * @expectation check if from & to are parsed correctly
     * @test
     */
    public function it_processes_flight_arrivals()
    {
        // GIVEN
        $domDocument = new DOMDocument();
        $domDocument->loadHTML($this->getHtml());
        $domXPath = new DOMXPath($domDocument);

        $listenerMock = Mockery::mock(ActivityProcessedListener::class);
        $listenerMock->shouldReceive('handle')->once();

        $this->app->instance(ActivityProcessedListener::class, $listenerMock);

        $app = $this->app;

        $activityDate = DateTime::createFromFormat('dMY', '10Jan2022');

        // WHEN
        $flightManager = $this->app->make(Flight::Class);
        $flightManager->processActivity($domXPath, $domDocument->documentElement, $activityDate);

        // THEN
        $parsedData = $flightManager->getLastEvent()->getActivityData();
        $this->assertInstanceOf(ActivityData::class, $parsedData);

        $from = $parsedData->getFrom();
        $to = $parsedData->getTo();

        $this->assertEquals("CPH", $from );
        $this->assertEquals( "KRP",$to);;

    }

    private function getHtml()
    {
        return '<table><tbody><tr id="ctl00_Main_activityGrid_5">
            <td class="lineLeft dontPrint expand-icon">
            <span class="glyphicon glyphicon-plus-sign align-glyphicon"></span>
            </td>
            <td class="lineLeft activitytablerow-date"></td>
            <td class="activitytablerow-revision visible-none-custom">&nbsp;</td>
            <td class="activitytablerow-dc visible-sm-custom">&nbsp;</td>
            <td class="activitytablerow-checkinlt">&nbsp;</td>
            <td class="activitytablerow-checkinutc">&nbsp;</td>
            <td class="activitytablerow-checkoutlt">&nbsp;</td>
            <td class="activitytablerow-checkoututc">&nbsp;</td>
            <td class="activitytablerow-activity">DX80</td>
            <td class="activitytablerow-activityRemark">DX 0080</td>
            <td class="lineLeft lineleft1">&nbsp;</td>
            <td class="activitytablerow-fromstn">CPH</td>
            <td class="activitytablerow-stdlt">1445</td>
            <td class="activitytablerow-stdutc">1345</td>
            <td class="lineLeft lineleft2">&nbsp;</td>
            <td class="activitytablerow-tostn">KRP</td>
            <td class="activitytablerow-stalt">1535</td>
            <td class="activitytablerow-stautc">1435</td>
            <td class="lineLeft lineleft3">&nbsp;</td>
            <td class="activitytablerow-AC/Hotel">
            DO4
            </td>
            <td class="activitytablerow-blockhours">&nbsp;</td>
            <td class="activitytablerow-flighttime visible-none-custom">&nbsp;</td>
            <td class="activitytablerow-nighttime visible-none-custom">&nbsp;</td>
            <td class="activitytablerow-duration">&nbsp;</td>
            <td class="activitytablerow-counter1">&nbsp;</td>
            <td class="lineLeft lineleft4">&nbsp;</td>
            <td class="activitytablerow-Paxbooked visible-none-custom">&nbsp;</td>
            <td class="activitytablerow-Tailnumber">OYJRY</td>
            <td class="activitytablerow-CrewMeal">&nbsp;</td>
            <td class="lineLeft lineleft5">&nbsp;</td>
            <td class="activitytablerow-Resources visible-none-custom">&nbsp;</td>
            <td class="activitytablerow-crewcodelist">|</td>
            <td class="activitytablerow-fullnamelist visible-none-custom">|</td>
            <td class="activitytablerow-positionlist">|</td>
            <td class="activitytablerow-BusinessPhoneList visible-none-custom">

            </td>
            <td class="activitytablerow-OtherDHCrewCode">&nbsp;</td>
            <td class="activitytablerow-DHFullNameList visible-none-custom">&nbsp;</td>
            <td class="activitytablerow-DHSeatingList visible-none-custom">&nbsp;</td>
            <td class="activitytablerow-remarks">&nbsp;</td>
            <td class="activitytablerow-ActualFdpTime">&nbsp;</td>
            <td class="activitytablerow-MaxFdpTime">&nbsp;</td>
            <td class="activitytablerow-RestCompletedTime visible-none-custom">&nbsp;</td>
            <td class="lineRight lineright1">&nbsp;</td>
            </tr></tbody></table>';
    }
}
