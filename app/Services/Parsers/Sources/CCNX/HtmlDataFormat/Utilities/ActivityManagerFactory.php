<?php

namespace App\Services\Parsers\Sources\CCNX\HtmlDataFormat\Utilities;


use App\Contracts\ActivityManager;
use App\Services\Parsers\Sources\CCNX\HtmlDataFormat\ActivityManagers\DayOff;
use App\Services\Parsers\Sources\CCNX\HtmlDataFormat\ActivityManagers\Flight;
use App\Services\Parsers\Sources\CCNX\HtmlDataFormat\ActivityManagers\Standby;
use App\Services\Parsers\Sources\CCNX\HtmlDataFormat\ActivityManagers\Unknown;
use Illuminate\Contracts\Foundation\Application;

/*
 * This factory is responsible to create a proper activity manager object, that will process
 * data parsing for specific kind of action
 */
class ActivityManagerFactory
{
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function create($activityType): ActivityManager
    {
        if ($this->isFlight($activityType)) {
            return $this->app->make(Flight::class);
        }

        switch ($activityType) {
            case 'OFF':
                return $this->app->make(DayOff::class);
            case 'SBY':
                return $this->app->make(Standby::class);
            case 'CAR':
            case 'NO DATA FOUND':
            default:
                return $this->app->make(Unknown::class);
        }
    }

    private function isFlight($activityType) : bool
    {
        $regex = '/^\w{2}\d+$/';
        return preg_match($regex, $activityType);
    }
}
