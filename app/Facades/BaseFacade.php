<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

abstract class BaseFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return static::key;
    }

    public static function run($class): void
    {
        app()->singleton(self::getFacadeAccessor(), $class);
    }
}
