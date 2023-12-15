<?php

namespace App\Enums\Models\Trip;

enum Status: int
{
    case ASSIGNED = 1;
    case AT_VENDOR = 2;
    case PICKED = 3;
    case DELIVERED = 4;

    public function string(): string
    {
        return match ($this) {
            self::ASSIGNED => 'Assigned',
            self::AT_VENDOR => 'At vendor',
            self::PICKED => 'Picked',
            self::DELIVERED => 'Delivered',
        };
    }

    public function isDelivered(): bool
    {
        return $this === self::DELIVERED;
    }
}
