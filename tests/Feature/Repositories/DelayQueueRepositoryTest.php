<?php

namespace Repositories;

use App\Models\DelayQueue;
use App\Repositories\DelayQueueRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DelayQueueRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_throws_exception_if_there_is_no_pending_delay_queue(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('There is no queued delay that not assigned yet, we are doing great');

        $repository = new DelayQueueRepository();
        $repository->findFirstToInvestigate();
    }

    public function test_return_first_pending_delay_queue()
    {
        $delayQueue = DelayQueue::factory()->create(['agent_id' => null]);

        $repository = new DelayQueueRepository();
        $result = $repository->findFirstToInvestigate();

        $this->assertEquals($delayQueue->toArray(), $result->toArray());
    }

    public function test_can_allocate_a_delayed_queue_to_an_agent()
    {
        $delayQueue = DelayQueue::factory()->create(['agent_id' => null]);
        $agentId = 1;

        $repository = new DelayQueueRepository();
        $repository->allocate($delayQueue, $agentId);

        $this->assertDatabaseHas('delay_queues', ['agent_id' => $agentId]);
    }
}
