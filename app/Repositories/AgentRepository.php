<?php

namespace App\Repositories;

use App\Models\Agent;
use Exception;

class AgentRepository
{
    /**
     * @throws Exception
     */
    public function shouldBeFree(int $agentId): void
    {
        $agent = Agent::query()->findOrFail($agentId);
        if (!is_null($agent->delayQueue)) {
            throw new Exception('you have one delayed order in progress, can not investigate another one');
        }
    }
}
