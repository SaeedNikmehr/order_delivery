<?php

namespace Tests\Feature\Repositories;

use App\Models\Agent;
use App\Models\DelayQueue;
use App\Repositories\AgentRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AgentRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_throws_exception_if_order_has_delay_queue(): void
    {
        $agent = Agent::factory()->has(DelayQueue::factory())->create();

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('you have one delayed order in progress, can not investigate another one');

        $repository = new AgentRepository();
        $repository->shouldBeFree($agent->id);
    }

}
