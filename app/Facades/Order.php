<?php

namespace App\Facades;

/**
 * @method static void shouldBelongsToUser(\App\Models\Order $order, int $userId)
 * @method static void shouldBeDelayed(\App\Models\Order $order)
 * @method static array handleDelayedOrder(\App\Models\Order $order)
 *
 * @see \App\Repositories\OrderRepository
 */
class Order extends BaseFacade
{
    const key = 'order.facade';
}
