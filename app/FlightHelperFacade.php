<?php

namespace App;

use Illuminate\Support\Facades\Facade;

class FlightHelperFacade extends Facade
{
    protected static function getFacadeAccessor() {
        return 'FlightHelper';
    }
}