<?php

namespace G4\Runner\Route;

class RouteFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testReconstitute()
    {
        $routeFactory = new RouteFactory([
            'module' => 'module-name',
            'service' => 'service-name'
        ]);
        $route = $routeFactory->reconstitute();

        $this->assertInstanceOf(Route::class, $route);
        $this->assertEquals($route->module(), 'module-name');
        $this->assertEquals($route->service(), 'service-name');
    }
}
