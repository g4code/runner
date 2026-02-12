<?php

namespace G4\Runner\Exception;

use G4\Runner\Route\ErrorCodes;
use PHPUnit\Framework\TestCase;

class InvalidModuleExceptionTest extends TestCase
{
    public function testExceptionMessage(): void
    {
        $moduleName = 'invalid_module';
        $exception = new InvalidModuleException($moduleName);
        
        $this->assertStringContainsString($moduleName, $exception->getMessage());
        $this->assertStringContainsString('Module is not valid', $exception->getMessage());
    }

    public function testExceptionCode(): void
    {
        $exception = new InvalidModuleException('test');
        
        $this->assertEquals(ErrorCodes::INVALID_MODULE, $exception->getCode());
    }
}
