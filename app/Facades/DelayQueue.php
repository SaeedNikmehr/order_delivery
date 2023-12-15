<?php

namespace App\Facades;

/**
 * @method static object findFirstToInvestigate()
 * @method static void allocate(object $delayQueue, int $agentId)
 *
 * @see \App\Repositories\DelayQueueRepository
 */
class DelayQueue extends BaseFacade
{
    const key = 'delay.queue.facade';
}
