<?php

namespace Vendor\Aura\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Vendor\Aura\Aura
 */
class Aura extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Vendor\Aura\Aura::class;
    }
}
