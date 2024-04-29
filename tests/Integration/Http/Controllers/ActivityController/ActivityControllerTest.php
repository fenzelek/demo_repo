<?php

namespace Http\Controllers\ActivityController;

use App\Models\Activity;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActivityControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();


        Activity::factory()->create([
            'Type' => 'FLT',
            'From' => 'LocationA',
            'To' => 'DestinationA',
            'Date' => now()->subDays(1),
        ]);

        Activity::factory()->create([
            'Type' => 'SBY',
            'From' => 'LocationB',
            'To' => 'DestinationB',
            'Date' => now()->addDays(2),
        ]);

        Activity::factory()->create([
            'Type' => 'FLT',
            'From' => 'LocationC',
            'To' => 'DestinationC',
            'Date' => now()->addDays(3),
        ]);

        Activity::factory()->create([
            'Type' => 'CI',
            'From' => 'LocationC',
            'To' => 'DestinationC',
            'Date' => now()->addDays(4),
        ]);

        Activity::factory()->create([
            'Type' => 'FLT',
            'From' => 'LocationA',
            'To' => 'DestinationB',
            'Date' => now()->addDays(5),
        ]);
    }

    /**
     * @Feature Activities
     * @scenario get
     * @case Test filtering activities based on different scenarios.
     * @expectation return expected amount of records from db
     *
     *
     * @dataProvider filterScenarios
     * @param string|null $actionType
     * @param array|null $dateRange
     * @param array|null $location
     * @param int $expectedCount
     * @return void
     */
    public function test_get_activities_filtered($actionType, $dateRange, $location, $expectedCount)
    {
        // GIVEN
        $parameters = [];
        if ($actionType !== null) {
            $parameters['actionType'] = $actionType;
        }
        if ($dateRange !== null) {
            $parameters += $dateRange;
        }
        if ($location !== null) {
            $parameters += $location;
        }
        $route = route('get-activities', $parameters);

        // WHEN
        $response = $this->get($route);

        // THEN
        $response->assertStatus(200);
        $response->assertJsonCount($expectedCount, "data");
    }

    /**
     * Data provider for filtering test cases.
     *
     * @return array
     */
    public function filterScenarios(): array
    {
        return [
            'without_filters' => [null, null, null, 5],
            'filtered_by_type_FLT' => ['FLT', null, null, 3],
            'filtered_by_date_range' => [null, ['startDate' => now()->toDateString(), 'endDate' => now()->addDays(3)->toDateString()], null, 2],
            'filtered_by_location_from' => [null, null, ['locationFrom' => 'LocationA'], 2],
            'filtered_by_location_to' => [null, null, ['locationTo' => 'DestinationC'], 2],
            'filtered_by_locationTo_and_Flight' => ['FLT', null, ['locationTo' => 'DestinationC'], 1],
            'filtered_by_locationTo_and_Checkout' => ['CO', null, ['locationTo' => 'DestinationC'], 0],
        ];
    }

    //TODO ad here some test for the response format check - like a smoke test

}
