<?php

namespace G4\Runner\Route;

use G4\ValueObject\StringLiteral;
use PHPUnit\Framework\TestCase;

class RouteFormatterTest extends TestCase
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
