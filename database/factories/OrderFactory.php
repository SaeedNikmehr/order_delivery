<?php

namespace Database\Factories;

use App\Enums\Models\Order\Status;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    public function definition(): array
    {
        return [
            "vendor_id" => random_int(1, 2),
            "user_id" => random_int(1, 2),
            "time_delivery" => random_int(10, 60),
            "status" => Status::IN_PROGRESS,
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (Order $order) {
            $dateDelivery = $order->created_at->addMinutes($order->time_delivery);

            $order->update([
                'date_delivery' => $dateDelivery,
            ]);
        });
    }

}
