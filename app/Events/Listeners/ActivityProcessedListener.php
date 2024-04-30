<?php

namespace App\Events\Listeners;

use App\Events\ActivityProcessed;
use App\Models\Activity;
use Illuminate\Contracts\Queue\ShouldQueue;

class ActivityProcessedListener implements ShouldQueue
{
    /**
     * Handle the event.
     *
     * @param  ActivityProcessed  $event
     * @return void
     */
    public function handle(ActivityProcessed $event)
    {
        //TODO probably we should replace that by calling this logic from some mapper / repository in database layer
        $activityData = $event->getActivityData();

        $activity = new Activity();

        $activity->flightnumber = $activityData->getFlightNumber();
        $activity->type = $activityData->getType();
        $activity->from = $activityData->getFrom();
        $activity->to = $activityData->getTo();
        $activity->start = $activityData->getStart()?$activityData->getStart()->format("Y/m/d H:i:s"):null;
        $activity->end = $activityData->getEnd()?$activityData->getEnd()->format("Y/m/d H:i:s"):null;
        $activity->date = $activityData->getDate();

        $activity->save();
    }
}
