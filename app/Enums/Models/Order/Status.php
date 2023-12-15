<?php

namespace App\Enums\Models\Order;

enum Status: int
{
    case IN_PROGRESS = 1;
    case DELAYED = 2;
    case DELIVERED = 3;

    public function string(): string
    {
        return match ($this) {
            self::IN_PROGRESS => 'In Progress',
            self::DELAYED => 'Delayed',
            self::DELIVERED => 'Delivered',
        };
    }

    public function isInProgress(): bool
    {
        return $this === self::IN_PROGRESS;
    }

    public function isDelayed(): bool
    {
        return $this === self::DELAYED;
    }

    public function isDelivered(): bool
    {
        return $this === self::DELIVERED;
    }
}
