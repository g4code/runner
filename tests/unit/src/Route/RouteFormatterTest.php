<?php

namespace G4\Runner\Route;

use G4\ValueObject\StringLiteral;
use PHPUnit\Framework\TestCase;

class RouteFormatterTest extends TestCase
{
    public function testFormat(): void
    {
        $route = new Route(new StringLiteral('test-module'), new StringLiteral('test-service'));
        
        $result = RouteFormatter::format($route);
        
        $this->assertIsArray($result);
        $this->assertArrayHasKey('module', $result);
        $this->assertArrayHasKey('service', $result);
        $this->assertEquals('test-module', $result['module']);
        $this->assertEquals('test-service', $result['service']);
    }

    public function testFormatWithDifferentValues(): void
    {
        $route = new Route(new StringLiteral('user'), new StringLiteral('profile'));
        
        $result = RouteFormatter::format($route);
        
        $this->assertIsArray($result);
        $this->assertEquals('user', $result['module']);
        $this->assertEquals('profile', $result['service']);
    }
}
