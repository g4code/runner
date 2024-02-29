<?php

namespace G4\Runner\Route;

use G4\Runner\Exception\InvalidModuleException;
use G4\Runner\Exception\InvalidServiceException;
use G4\ValueObject\StringLiteral;
use PHPUnit\Framework\TestCase;

class RouteTest extends TestCase
{
    /**
     * @param $module
     * @dataProvider invalidModuleAndServiceStrings
     */
    public function testConstructWithInvalidModuleNames($module)
    {
        $this->expectException(InvalidModuleException::class);
        new Route(new StringLiteral($module), new StringLiteral('service'));
    }

    /**
     * @param $service
     * @dataProvider invalidModuleAndServiceStrings
     */
    public function testConstructWithInvalidServiceNames($service)
    {
        $this->expectException(InvalidServiceException::class);
        new Route(new StringLiteral('module'), new StringLiteral($service));
    }

    /**
     * @dataProvider validModuleAndServiceStrings
     */
    public function testConstructWithValidArguments($value)
    {
        $route = new Route(new StringLiteral($value), new StringLiteral($value));
        $this->assertEquals($route->service(), $value);
        $this->assertEquals($route->module(), $value);
    }

    public function invalidModuleAndServiceStrings()
    {
        return [
            ['front/'],
            ['*light'],
            [';asd'],
            ['some_module'],
            [',module1+'],
        ];
    }

    public function validModuleAndServiceStrings()
    {
        return [
            ['front'],
            ['light'],
            ['some-name'],
            ['some-weird-name2'],
            ['danijel-is-awesome'],
        ];
    }
}
