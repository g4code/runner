<?php

namespace G4\Runner\Exception;

use G4\Runner\Route\ErrorCodes;
use PHPUnit\Framework\TestCase;

class InvalidServiceExceptionTest extends TestCase
{
    public function testExceptionMessage(): void
    {
        $serviceName = 'invalid_service';
        $exception = new InvalidServiceException($serviceName);
        
        $this->assertStringContainsString($serviceName, $exception->getMessage());
        $this->assertStringContainsString('Service is not valid', $exception->getMessage());
    }

    public function testExceptionCode(): void
    {
        $exception = new InvalidServiceException('test');
        
        $this->assertEquals(ErrorCodes::INVALID_SERVICE, $exception->getCode());
    }
}
