<?php

namespace Services\Parsers\Sources\CCNX\HTML\Utilities;

use App\Services\Parsers\Sources\CCNX\HtmlDataFormat\ActivityManagers\Flight;
use App\Services\Parsers\Sources\CCNX\HtmlDataFormat\ActivityManagers\Standby;
use App\Services\Parsers\Sources\CCNX\HtmlDataFormat\ActivityManagers\Unknown;
use App\Services\Parsers\Sources\CCNX\HtmlDataFormat\Utilities\ActivityManagerFactory;
use Carbon\Carbon;
use Tests\TestCase;

/*
 * We don't need to test all cases, as the factory switch is quite simple
 * What is important is to test if the regular expression is correct and produce proper
 * object
 */
class ActivityManagerFactoryTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @Feature parse roster data
     * @scenario html document
     * @case Flight activities
     * @expectation Flight Activity manager created
     * @test
     */
    public function create_FlightInstance(): void
    {
        // GIVEN
        // a sample activity type representing a flight
        $activityType = 'DX77';
        $factory = $this->app->make(ActivityManagerFactory::class);

        // WHEN
        $activityManager = $factory->create($activityType, null, null);

        // THEN
        $this->assertInstanceOf(Flight::class, $activityManager);
    }

    /**
     * @Feature parse roster data
     * @scenario html document
     * @case Standby activities
     * @expectation Standby Activity manager created
     * @test
     */
    public function create_StandbyInstance(): void
    {
        // GIVEN
        // a sample activity type representing standby
        $activityType = 'SBY';
        $factory = $this->app->make(ActivityManagerFactory::class);

        // WHEN
        $activityManager = $factory->create($activityType, new Carbon(), new Carbon());

        // THEN
        $this->assertInstanceOf(Standby::class, $activityManager);
    }

    /**
     * @Feature parse roster data
     * @scenario html document
     * @case Unknown activities
     * @expectation Unknown Activity manager created
     * @test
     */
    public function testCreateUnknownInstance(): void
    {
        // GIVEN
        // a sample random activity type
        $activityType = 'CAR';
        $factory = $this->app->make(ActivityManagerFactory::class);

        // WHEN
        $activityManager = $factory->create($activityType, null, new Carbon());

        // THEN
        $this->assertInstanceOf(Unknown::class, $activityManager);
    }
}
