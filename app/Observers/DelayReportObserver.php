<?php

namespace App\Observers;

use App\Models\DelayReport;

class DelayReportObserver
{
    public function created(DelayReport $delayReport): void
    {
        if (!is_null($delayReport->time_delivery)) {
            $delayReport->update(['date_delivery' => $delayReport->created_at->addMinutes($delayReport->time_delivery)]);
        }
    }
}
