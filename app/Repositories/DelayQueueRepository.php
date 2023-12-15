<?php

namespace App\Repositories;

use App\Models\DelayQueue;
use Exception;

class DelayQueueRepository
{
    /**
     * @throws Exception
     */
    public function findFirstToInvestigate(): object
    {
        $delayQueue = DelayQueue::query()->whereNull('agent_id')->first();
        if (is_null($delayQueue)) {
            throw new Exception('There is no queued delay that not assigned yet, we are doing great');
        }

        return $delayQueue;
    }

    public function allocate(object $delayQueue, int $agentId): void
    {
        $delayQueue->update(['agent_id' => $agentId]);
    }
}
