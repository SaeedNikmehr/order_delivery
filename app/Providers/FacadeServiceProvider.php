<?php

namespace App\Providers;

use App\Facades\Agent;
use App\Facades\DelayQueue;
use App\Facades\Order;
use App\Facades\Response;
use App\Facades\Vendor;
use App\Repositories\AgentRepository;
use App\Repositories\DelayQueueRepository;
use App\Repositories\OrderRepository;
use App\Repositories\VendorRepository;
use Illuminate\Support\ServiceProvider;

class FacadeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        Response::run(\App\Utils\Response::class);
        Order::run(OrderRepository::class);
        Agent::run(AgentRepository::class);
        DelayQueue::run(DelayQueueRepository::class);
        Vendor::run(VendorRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
