<?php

namespace Tests\Feature\Controllers;

use App\Facades\DelayQueue;
use App\Models\Agent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class AllocateDelayControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_throws_exception_if_agent_is_not_free(): void
    {
        $agent = Agent::factory()->create();

        \App\Facades\Agent::shouldReceive('shouldBeFree')->once()->with($agent->id)
            ->andThrow(new \Exception(), 'you have one delayed order in progress, can not investigate another one');
        DelayQueue::shouldReceive('findFirstToInvestigate')->never();
        DelayQueue::shouldReceive('allocate')->never();

        $response = $this->patchJson(route('delays.allocate'), [
            'agent_id' => $agent->id,
        ]);

        $response->assertJson(fn(AssertableJson $json) => $json->where('status', 'error')
            ->has('data')
            ->has('errors')
            ->etc()
        );
    }

    public function test_throws_exception_if_there_are_no_pending_delay_queue_record(): void
    {
        $agent = Agent::factory()->create();

        \App\Facades\Agent::shouldReceive('shouldBeFree')->once()->with($agent->id)->andReturnNull();
        DelayQueue::shouldReceive('findFirstToInvestigate')->once()
            ->andThrow(new \Exception(), 'There is no queued delay that not assigned yet, we are doing great');
        DelayQueue::shouldReceive('allocate')->never();

        $response = $this->patchJson(route('delays.allocate'), [
            'agent_id' => $agent->id,
        ]);

        $response->assertJson(fn(AssertableJson $json) => $json->where('status', 'error')
            ->has('data')
            ->has('errors')
            ->etc()
        );
    }

    public function test_can_allocate_a_delay_queue_record_to_an_agent(): void
    {
        $agent = Agent::factory()->create();
        $delayQueue = \App\Models\DelayQueue::factory()->create();

        \App\Facades\Agent::shouldReceive('shouldBeFree')->once()->with($agent->id)->andReturnNull();
        DelayQueue::shouldReceive('findFirstToInvestigate')->once()->andReturn($delayQueue);
        DelayQueue::shouldReceive('allocate')->once()->andReturnNull();

        $response = $this->patchJson(route('delays.allocate'), [
            'agent_id' => $agent->id,
        ]);

        $response->assertSuccessful();
        $response->assertJson(fn(AssertableJson $json) => $json->where('status', 'success')
            ->where('message', 'This delayed order was assigned to you to investigate, please make us proud')
            ->has('data')
            ->has('errors')
            ->etc()
        );
    }
}
