<?php

namespace App\Facades;

/**
 * @method static void shouldBeFree(int $agentId)
 *
 * @see \App\Repositories\AgentRepository
 */
class Agent extends BaseFacade
{
    const key = 'agent.facade';
}
