<?php

namespace G4\Runner\Route;

use G4\ValueObject\StringLiteral;

class RouteFormatterTest extends \PHPUnit_Framework_TestCase
{
    public function testFormat()
    {
        $route = new Route(new StringLiteral('module-name'), new StringLiteral('service-name'));
        $expectedArray = [
            'module' => 'module-name',
            'service' => 'service-name'
        ];

        $this->assertEquals($expectedArray, (new RouteFormatter())->format($route));
    }
}
