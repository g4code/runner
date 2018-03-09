<?php

namespace G4\Runner\Route;

class RouteFormatter
{
    public static function format(Route $route)
    {
        return [
            RouteConstants::MODULE => $route->module(),
            RouteConstants::SERVICE => $route->service()
        ];
    }
}