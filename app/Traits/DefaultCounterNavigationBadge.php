<?php

namespace App\Traits;

trait DefaultCounterNavigationBadge
{
    public static function getNavigationBadge(): ?string
    {
        return static::$model::count();
    }
}
