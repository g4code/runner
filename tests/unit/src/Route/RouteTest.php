<?php

namespace G4\Runner\Route;

use G4\ValueObject\StringLiteral;

class RouteTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @param $module
     * @dataProvider invalidModuleAndServiceStrings
     * @expectedException G4\Runner\Exception\InvalidModuleException
     */
    public function testConstructWithInvalidModuleNames($module)
    {
        new Route(new StringLiteral($module), new StringLiteral('service'));
    }

    /**
     * @test
     * @param $service
     * @dataProvider invalidModuleAndServiceStrings
     * @expectedException G4\Runner\Exception\InvalidServiceException
     */
    public function testConstructWithInvalidServiceNames($service)
    {
        new Route(new StringLiteral('module'), new StringLiteral($service));
    }

    /**
     * @test
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
