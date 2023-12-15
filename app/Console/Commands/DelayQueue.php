<?php

namespace App\Console\Commands;

use App\Enums\Models\Order\Status;
use App\Models\Agent;
use App\Models\Order;
use App\Models\User;
use Illuminate\Console\Command;

class DelayQueue extends Command
{
    protected $signature = 'delay_queue';

    protected $description = 'Create required records in database to be able to assign a delay queue to an agent';

    public function handle(): void
    {
        $user = User::query()->first();
        $agent = Agent::factory()->create();
        Order::factory()->has(\App\Models\DelayQueue::factory()->sequence(['agent_id' => null]))->create([
            'user_id' => $user->id,
            'status' => Status::DELAYED,
            'date_delivery' => now()->subMinutes(60),
            'created_at' => now()->subMinutes(60),
        ]);

        $this->info('Agent ID : ' . $agent->id);
    }
}
