<?php

namespace Database\Factories;

use App\Enums\Models\Trip\Status;
use App\Models\Trip;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Trip>
 */
class TripFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_id' => random_int(1, 2),
            'courier_id' => random_int(1, 2),
            'status' => fake()->randomElement([Status::ASSIGNED, Status::AT_VENDOR, Status::PICKED, Status::DELIVERED])
        ];
    }
}
