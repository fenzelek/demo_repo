<?php

namespace Services\Parsers\Sources\CCNX\HTML;

use App\Contracts\ActivityManager;
use App\Services\Parsers\Sources\CCNX\HtmlDataFormat\ActivityManagers\DayOff;
use App\Services\Parsers\Sources\CCNX\HtmlDataFormat\ActivityManagers\Flight;
use App\Services\Parsers\Sources\CCNX\HtmlDataFormat\ActivityManagers\Standby;
use App\Services\Parsers\Sources\CCNX\HtmlDataFormat\HtmlRosterParser;
use App\Services\Parsers\Sources\CCNX\HtmlDataFormat\Utilities\ActivityManagerFactory;
use Illuminate\Http\UploadedFile;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase as TestCase;


class HtmlRosterParserTest extends TestCase
{
    private $htmlFile;

    protected function setUp(): void
    {
        parent::setUp();
        $this->htmlFile = new UploadedFile(__DIR__ . '/Roster - CrewConnex.html', 'Roster - CrewConnex.html');
    }

    /**
     * @Feature parse roster data
     * @scenario html document
     * @case Day Off activities
     * @expectation Day off activity fired 5 times as we have in example data
     * @test
     */
    public function parse_factoryExecuted_dayoff(int $timesCalled = 5)
    {
        //GIVEN
        $dayOffManagerMock = Mockery::mock(DayOff::class);
        $dayOffManagerMock->shouldReceive('processActivity')->times($timesCalled);

        $otherActivities = Mockery::mock(ActivityManager::class);
        $otherActivities->shouldReceive('processActivity');

        $factoryMock = Mockery::mock(ActivityManagerFactory::class);
        App()->instance(ActivityManagerFactory::class, $factoryMock);

        $parser = App()->make(HtmlRosterParser::class);

        //THEN
        $factoryMock->shouldReceive('create')->with('OFF')->andReturn($dayOffManagerMock);
        $factoryMock->shouldReceive('create')->andReturn($otherActivities);

        //WHEN
        $parser->parse($this->htmlFile);

    }

    /**
     * @Feature parse roster data
     * @scenario html document
     * @case Standby activities
     * @expectation Standby activity fired 2 times as we have in example data
     * @test
     */
    public function parse_factoryExecuted_standby(int $timesCalled = 2)
    {
        //GIVEN
        $standbyManagerMock = Mockery::mock(Standby::class);
        $standbyManagerMock->shouldReceive('processActivity')->times($timesCalled);

        $otherActivities = Mockery::mock(ActivityManager::class);
        $otherActivities->shouldReceive('processActivity');

        $factoryMock = Mockery::mock(ActivityManagerFactory::class);
        App()->instance(ActivityManagerFactory::class, $factoryMock);

        $parser = App()->make(HtmlRosterParser::class);

        //THEN
        $factoryMock->shouldReceive('create')->with('SBY')->andReturn($standbyManagerMock);
        $factoryMock->shouldReceive('create')->andReturn($otherActivities);

        //WHEN
        $parser->parse($this->htmlFile);
    }

    /**
     * @Feature parse roster data
     * @scenario html document
     * @case Flight activities
     * @expectation Flight activity fired 26 times as we have in example data
     * @test
     */
    public function parse_factoryExecuted_flight(int $timesCalled = 26)
    {
        //GIVEN
        $flightManagerMock = Mockery::mock(Flight::class);
        $flightManagerMock->shouldReceive('processActivity')->times($timesCalled);

        $otherActivities = Mockery::mock(ActivityManager::class);
        $otherActivities->shouldReceive('processActivity');

        $factoryMock = Mockery::mock(ActivityManagerFactory::class);
        App()->instance(ActivityManagerFactory::class, $factoryMock);

        $parser = App()->make(HtmlRosterParser::class);

        //THEN
        $factoryMock->shouldReceive('create')
                    ->with(Mockery::pattern('/^\w{2}\d+$/'))
                    ->andReturn($flightManagerMock);

        $factoryMock->shouldReceive('create')->andReturn($otherActivities);

        //WHEN
        $parser->parse($this->htmlFile);
    }
}
