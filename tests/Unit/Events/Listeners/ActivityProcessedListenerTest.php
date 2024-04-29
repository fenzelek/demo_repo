<?php

namespace Events\Listeners;

use App\Contracts\ActivityData;
use App\Events\ActivityProcessed;
use App\Events\Listeners\ActivityProcessedListener;
use App\Models\ValueObjects\ActivityType;
use Carbon\Carbon;
use DateTime;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActivityProcessedListenerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_creates_activity_from_processed_data()
    {
        // GIVEN
        $listener = new ActivityProcessedListener();
        $activityDataMock = $this->getMockBuilder(ActivityData::class)
            ->disableOriginalConstructor()
            ->getMock();
        $flightNumber = 'DX80';
        $from = 'KRK';
        $to = 'CHR';
        $activityDate = new DateTime('2022-01-10 00:00:00');
        $start = Carbon::createFromFormat('Y-m-d H:i:s', $activityDate->format('Y-m-d') . ' 13:45:00');
        $end = Carbon::createFromFormat('Y-m-d H:i:s', $activityDate->format('Y-m-d') . ' 14:50:00');

        $activityDataMock->method('getFlightNumber')->willReturn($flightNumber);
        $activityDataMock->method('getType')->willReturn(ActivityType::FLIGHT);
        $activityDataMock->method('getFrom')->willReturn($from);
        $activityDataMock->method('getTo')->willReturn($to);
        $activityDataMock->method('getStart')->willReturn($start);
        $activityDataMock->method('getEnd')->willReturn($end);
        $activityDataMock->method('getDate')->willReturn($activityDate);


        $event = new ActivityProcessed($activityDataMock);

        // WHEN
        $listener->handle($event);

        // THEN
        $this->assertDatabaseHas('activities', [
            'flightnumber' => $flightNumber,
            'type' => ActivityType::FLIGHT,
            'from' => $from,
            'to' => $to,
            'start' => $start->format("Y/m/d H:i:s"),
            'end' => $end->format("Y/m/d H:i:s"),
            'date' => $activityDate->format("Y-m-d H:i:s"),
        ]);
    }
}

