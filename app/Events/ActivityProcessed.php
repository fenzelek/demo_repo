<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Contracts\ActivityData;
class ActivityProcessed
{
    use Dispatchable, SerializesModels;

    protected $activityData;

    /**
     * Create a new event instance.
     *
     * @param ActivityData $activityData
     * @return void
     */
    public function __construct(ActivityData $activityData)
    {
        $this->activityData = $activityData;
    }

    public function getActivityData():ActivityData
    {
        return $this->activityData;
    }
}
