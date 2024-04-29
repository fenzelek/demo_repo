<?php

namespace Database\Factories;

use App\Models\Activity;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Activity>
 */
class ActivityFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Activity::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'FlightNumber' => $this->faker->randomElement(['DX80', 'DX77', 'DX83']),
            'Type' => $this->faker->randomElement(['FLT', 'SBY']),
            'From' => $this->faker->city,
            'To' => $this->faker->city,
            'Start' => $this->faker->dateTime(),
            'End' => $this->faker->dateTime(),
            'Date' => $this->faker->date(),
        ];
    }
}
