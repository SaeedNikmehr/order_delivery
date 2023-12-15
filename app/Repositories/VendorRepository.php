<?php

namespace App\Repositories;

use App\Models\Vendor;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;

class VendorRepository
{
    public function DelaysReport(): Collection|array
    {
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        return Vendor::query()
            ->whereHas('orders', function ($query) use ($startOfWeek, $endOfWeek) {
                $query->whereBetween('orders.created_at', [$startOfWeek, $endOfWeek]);
            })
            ->withSum('delayReports as total_delays_in_minutes', 'time_delivery')
            ->withCount('orders as total_orders')
            ->orderBy('total_delays_in_minutes', 'desc')
            ->get();
    }
}
