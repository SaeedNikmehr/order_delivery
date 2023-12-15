<?php

use Illuminate\Support\Carbon;
function formatDate(?object $date, string $format = 'Y-m-d H:i:s'): null|string
{
    return is_null($date) ? null : Carbon::parse($date)->format($format);
}
