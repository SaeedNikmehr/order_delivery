<?php

namespace App\Http\Controllers;

use App\Facades\Response;
use App\Http\Requests\ReportDelayRequest;
use App\Models\Order;

class ReportDelayController extends Controller
{
    public function __invoke(ReportDelayRequest $request, Order $order)
    {
        \App\Facades\Order::shouldBelongsToUser($order, $request->user_id);
        \App\Facades\Order::shouldBeDelayed($order);
        [$message, $data] = \App\Facades\Order::handleDelayedOrder($order);

        return Response::success()->message($message)->data($data);
    }
}
