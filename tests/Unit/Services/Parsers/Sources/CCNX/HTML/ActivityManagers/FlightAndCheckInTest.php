<?php

namespace Services\Parsers\Sources\CCNX\HTML\ActivityManagers;

use App\Contracts\ActivityData;
use App\Events\ActivityProcessed;
use App\Events\Listeners\ActivityProcessedListener;
use App\Models\ValueObjects\ActivityType;
use App\Services\Parsers\Sources\CCNX\HtmlDataFormat\ActivityManagers\Flight;
use App\Services\Parsers\Sources\CCNX\HtmlDataFormat\ActivityManagers\FlightAndCheckIn;
use DateTime;
use DOMDocument;
use DOMXPath;
use Illuminate\Contracts\Events\Dispatcher;
use Mockery;
use Tests\TestCase;

class FlightAndCheckInTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();

        libxml_use_internal_errors(true);
    }

    /**
     * @Feature parse roster data
     * @scenario html document
     * @case Check In activities
     * @expectation Check In activity data parsed and sent to the event,
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

        $eventDispatcher->shouldReceive('dispatch')->twice()->with(ActivityProcessed::class);
        $activityDate = DateTime::createFromFormat('dMY', '10Jan2022');
        // WHEN
        $flightManager = new FlightAndCheckIn($eventDispatcher, $app);
        $flightManager->processActivity($domXPath, $domDocument->documentElement, $activityDate);

        // THEN
        $this->assertNotNull($flightManager->getLastEvent());
        $this->assertInstanceOf(ActivityProcessed::class, $flightManager->getLastEvent());
    }

    /**
     * @Feature parse roster data
     * @scenario html document
     * @case Flight activities
     * @expectation check if the times are set properly for checkin where start is the checkin time
     * @test
     */
    public function it_processes_flight_time()
    {
        // GIVEN
        $domDocument = new DOMDocument();
        $domDocument->loadHTML($this->getHtml());
        $domXPath = new DOMXPath($domDocument);

        $listenerMock = Mockery::mock(ActivityProcessedListener::class);
        $listenerMock->shouldReceive('handle')->twice();

        $this->app->instance(ActivityProcessedListener::class, $listenerMock);

        $app = $this->app;

        $activityDate = DateTime::createFromFormat('dMY', '10Jan2022');

        // WHEN
        $flightManager = $this->app->make(FlightAndCheckIn::Class);
        $flightManager->processActivity($domXPath, $domDocument->documentElement, $activityDate);

        // THEN
        $parsedData = $flightManager->getLastEvent()->getActivityData();
        $this->assertInstanceOf(ActivityData::class, $parsedData);

        $start = $parsedData->getStart();
        $end = $parsedData->getEnd();
        $type = $parsedData->getType();

        $this->assertEquals("10 January 2022 07:45", $start->format('d F Y H:i'));
        $this->assertEquals(null, $end);
        $this->assertEquals( ActivityType::CHECK_IN, $type);

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
        $listenerMock->shouldReceive('handle')->twice();

        $this->app->instance(ActivityProcessedListener::class, $listenerMock);

        $app = $this->app;

        $activityDate = DateTime::createFromFormat('dMY', '10Jan2022');

        // WHEN
        $flightManager = $this->app->make(FlightAndCheckIn::Class);
        $flightManager->processActivity($domXPath, $domDocument->documentElement, $activityDate);

        // THEN
        $parsedData = $flightManager->getLastEvent()->getActivityData();
        $this->assertInstanceOf(ActivityData::class, $parsedData);

        $from = $parsedData->getFrom();
        $to = $parsedData->getTo();

        $this->assertEquals("KRP", $from );
        $this->assertEquals( "",$to);;
    }

    private function getHtml()
    {
        return '<table><tbody><tr id="ctl00_Main_activityGrid_4">
                    <td class="lineLeft dontPrint expand-icon">
                    <span class="glyphicon glyphicon-plus-sign align-glyphicon"></span>
                    </td>
                    <td class="lineLeft activitytablerow-date"><nobr>Tue 11</nobr></td>
                    <td class="activitytablerow-revision visible-none-custom">&nbsp;</td>
                    <td class="activitytablerow-dc visible-sm-custom">&nbsp;</td>
                    <td class="activitytablerow-checkinlt">0845</td>
                    <td class="activitytablerow-checkinutc">0745</td>
                    <td class="activitytablerow-checkoutlt">&nbsp;</td>
                    <td class="activitytablerow-checkoututc">&nbsp;</td>
                    <td class="activitytablerow-activity">DX77</td>
                    <td class="activitytablerow-activityRemark">DX 0077</td>
                    <td class="lineLeft lineleft1">&nbsp;</td>
                    <td class="activitytablerow-fromstn">KRP</td>
                    <td class="activitytablerow-stdlt">0945</td>
                    <td class="activitytablerow-stdutc">0845</td>
                    <td class="lineLeft lineleft2">&nbsp;</td>
                    <td class="activitytablerow-tostn">CPH</td>
                    <td class="activitytablerow-stalt">1035</td>
                    <td class="activitytablerow-stautc">0935</td>
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
                    <td class="activitytablerow-crewcodelist">
                    <nobr>LET</nobr><br><nobr>THI</nobr><br><nobr>ILV</nobr>
                    </td>
                    <td class="activitytablerow-fullnamelist visible-none-custom">

                    </td>
                    <td class="activitytablerow-positionlist">

                    </td>
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
