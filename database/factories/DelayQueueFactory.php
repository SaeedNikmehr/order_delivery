<?php

namespace Database\Factories;

use App\Models\DelayQueue;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DelayQueue>
 */
class DelayQueueFactory extends Factory
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
            'agent_id' => random_int(1, 2),
        ];
    }
}
