<?php

namespace Kim\JavaScript;

use Illuminate\Support\Facades\Facade;

class JavaScriptFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'javascript';
    }
}