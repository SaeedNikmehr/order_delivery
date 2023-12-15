<?php

namespace App\Http\Controllers;

use App\Facades\Agent;
use App\Facades\DelayQueue;
use App\Facades\Response;
use App\Http\Requests\AllocateDelayRequest;
use App\Http\Resources\AllocateResource;

class AllocateDelayController extends Controller
{
    public function __invoke(AllocateDelayRequest $request)
    {
        Agent::shouldBeFree($request->agent_id);
        $delayQueue = DelayQueue::findFirstToInvestigate();
        DelayQueue::allocate($delayQueue, $request->agent_id);

        return Response::success()
            ->message('This delayed order was assigned to you to investigate, please make us proud')
            ->data(AllocateResource::make($delayQueue));
    }
}
