<?php

namespace PrimitiveSocial\BlueMoonWrapper\Facades;

use Illuminate\Support\Facades\Facade;

class BlueMoonWrapper extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'bluemoonwrapper';
    }
}
