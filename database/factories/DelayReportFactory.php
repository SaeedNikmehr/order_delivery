<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DelayReport>
 */
class DelayReportFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $timeDelivery = random_int(10, 60);
        return [
            'order_id' => random_int(1, 2),
            'time_delivery' => $timeDelivery,
            'date_delivery' => now()->addMinutes($timeDelivery),
        ];
    }
}
