<?php

namespace Laravel\Aura\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Laravel\Aura\Aura
 */
class Aura extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Laravel\Aura\Aura::class;
    }
}
