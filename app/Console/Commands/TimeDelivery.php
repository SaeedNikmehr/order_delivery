<?php

namespace App\Console\Commands;

use App\Enums\Models\Order\Status;
use App\Models\Order;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Console\Command;

class TimeDelivery extends Command
{
    protected $signature = 'time_delivery';

    protected $description = 'Create required records in database to receive new time delivery when announce delay for an order';

    public function handle(): void
    {
        $user = User::query()->first();
        $order = Order::factory()->has(Trip::factory()->sequence(['status' => \App\Enums\Models\Trip\Status::ASSIGNED]))->create([
            'user_id' => $user->id,
            'status' => Status::DELAYED,
            'date_delivery' => now()->subMinutes(60),
            'created_at' => now()->subMinutes(60),
        ]);

        $this->info('Order ID : ' . $order->id);
        $this->info('User ID : ' . $user->id);
    }
}
