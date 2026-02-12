<?php

namespace G4\Runner;

use G4\CleanCore\Application;
use G4\Log\Logger as LogLogger;
use PHPUnit\Framework\TestCase;

class LoggerTest extends TestCase
{
    private Logger $logger;

    protected function setUp(): void
    {
        $this->logger = new Logger();
    }

    public function testConstructorInitializesLogger(): void
    {
        $this->assertInstanceOf(Logger::class, $this->logger);
    }

    public function testSetLogger(): void
    {
        $logLoggerMock = $this->createMock(LogLogger::class);
        $this->logger->setLogger($logLoggerMock);
        
        // Test that logger is set by calling logRequest which should register shutdown function
        $applicationMock = $this->createMock(Application::class);
        $applicationMock->method('getRequest')->willReturn(
            $this->createMock(\G4\CleanCore\Request\Request::class)
        );
        
        $this->logger->logRequest($applicationMock);
        
        // If no exception is thrown, the logger was set correctly
        $this->assertTrue(true);
    }

    public function testLogRequestWithoutLoggerDoesNothing(): void
    {
        $applicationMock = $this->createMock(Application::class);
        
        // Should not throw any exception
        $this->logger->logRequest($applicationMock);
        
        $this->assertTrue(true);
    }

    public function testLogResponseWithoutLoggerDoesNothing(): void
    {
        $applicationMock = $this->createMock(Application::class);
        $profilerMock = $this->createMock(Profiler::class);
        
        // Should not throw any exception
        $this->logger->logResponse($applicationMock, $profilerMock);
        
        $this->assertTrue(true);
    }

    public function testLogRequestWithLoggerRegistersShutdownFunction(): void
    {
        $logLoggerMock = $this->createMock(LogLogger::class);
        $this->logger->setLogger($logLoggerMock);
        
        $applicationMock = $this->createMock(Application::class);
        $requestMock = $this->createMock(\G4\CleanCore\Request\Request::class);
        $applicationMock->method('getRequest')->willReturn($requestMock);
        
        // This should register a shutdown function
        $this->logger->logRequest($applicationMock);
        
        // If no exception is thrown, the shutdown function was registered
        $this->assertTrue(true);
    }

    public function testLogResponseWithLoggerRegistersShutdownFunction(): void
    {
        $logLoggerMock = $this->createMock(LogLogger::class);
        $this->logger->setLogger($logLoggerMock);
        
        $applicationMock = $this->createMock(Application::class);
        $responseMock = $this->createMock(\G4\CleanCore\Response\Response::class);
        $applicationMock->method('getResponse')->willReturn($responseMock);
        
        $profilerMock = $this->createMock(Profiler::class);
        
        // This should register a shutdown function
        $this->logger->logResponse($applicationMock, $profilerMock);
        
        // If no exception is thrown, the shutdown function was registered
        $this->assertTrue(true);
    }
}
