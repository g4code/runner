<?php

namespace G4\Runner;

use G4\Profiler\Ticker\TickerAbstract;
use PHPUnit\Framework\TestCase;

class ProfilerTest extends TestCase
{
    private Profiler $profiler;

    protected function setUp(): void
    {
        $this->profiler = new Profiler();
    }

    public function testConstructorSetsDefaultLogLevel(): void
    {
        $this->assertInstanceOf(Profiler::class, $this->profiler);
    }

    public function testAddProfiler(): void
    {
        $tickerMock = $this->createMock(TickerAbstract::class);
        $result = $this->profiler->addProfiler($tickerMock);
        
        $this->assertInstanceOf(Profiler::class, $result);
    }

    public function testSetLogLevel(): void
    {
        $result = $this->profiler->setLogLevel(Profiler::LOG_OFF);
        
        $this->assertInstanceOf(Profiler::class, $result);
    }

    public function testSetThreshold(): void
    {
        $result = $this->profiler->setThreshold(1000);
        
        $this->assertInstanceOf(Profiler::class, $result);
    }

    public function testGetProfilerOutputReturnsEmptyArrayWhenNoProfilers(): void
    {
        $result = $this->profiler->getProfilerOutput(200);
        
        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    public function testGetProfilerOutputWithLogOff(): void
    {
        $tickerMock = $this->createMock(TickerAbstract::class);
        $this->profiler->addProfiler($tickerMock);
        $this->profiler->setLogLevel(Profiler::LOG_OFF);
        
        $result = $this->profiler->getProfilerOutput(200);
        
        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    public function testGetProfilerOutputWithLogAlways(): void
    {
        $tickerMock = $this->createMock(TickerAbstract::class);
        $tickerMock->method('getName')->willReturn('test_profiler');
        $tickerMock->method('getFormatted')->willReturn(['test' => 'data']);
        $tickerMock->method('getQueries')->willReturn([]);
        
        $this->profiler->addProfiler($tickerMock);
        $this->profiler->setLogLevel(Profiler::LOG_ALWAYS);
        
        $result = $this->profiler->getProfilerOutput(200, 0, 0);
        
        $this->assertIsArray($result);
        $this->assertEquals(['unsuported request parameter'], $result);
    }

    public function testGetProfilerOutputWithDbProfiler1(): void
    {
        $tickerMock = $this->createMock(TickerAbstract::class);
        $tickerMock->method('getName')->willReturn('test_profiler');
        $tickerMock->method('getFormatted')->willReturn(['test' => 'data']);
        $tickerMock->method('getQueries')->willReturn([]);
        
        $this->profiler->addProfiler($tickerMock);
        $this->profiler->setLogLevel(Profiler::LOG_ALWAYS);
        
        $result = $this->profiler->getProfilerOutput(200, 1, 0);
        
        $this->assertIsArray($result);
        $this->assertArrayHasKey('test_profiler', $result);
        $this->assertEquals(['test' => 'data'], $result['test_profiler']);
    }

    public function testGetProfilerOutputWithDbProfiler2(): void
    {
        $tickerMock = $this->createMock(TickerAbstract::class);
        $tickerMock->method('getName')->willReturn('test_profiler');
        $tickerMock->method('getFormatted')->willReturn(['test' => 'data']);
        $tickerMock->method('getQueries')->willReturn([1 => ['query' => 'SELECT *']]);
        
        $this->profiler->addProfiler($tickerMock);
        $this->profiler->setLogLevel(Profiler::LOG_ALWAYS);
        
        $result = $this->profiler->getProfilerOutput(200, 2, 0);
        
        $this->assertIsArray($result);
        $this->assertArrayHasKey(1, $result);
    }

    public function testIsRequestThresholdExceeded(): void
    {
        $this->profiler->setThreshold(1000);
        
        $this->assertTrue($this->profiler->isRequestTresholdExceded(1500));
        $this->assertFalse($this->profiler->isRequestTresholdExceded(500));
    }

    public function testIsRequestThresholdExceededWithNoThreshold(): void
    {
        $this->assertFalse($this->profiler->isRequestTresholdExceded(1500));
    }

    public function testGetTaskerProfilerOutputReturnsNullWhenNoProfilers(): void
    {
        $result = $this->profiler->getTaskerProfilerOutput(2);
        
        $this->assertNull($result);
    }

    public function testGetTaskerProfilerOutputWithLogOff(): void
    {
        $tickerMock = $this->createMock(TickerAbstract::class);
        $this->profiler->addProfiler($tickerMock);
        $this->profiler->setLogLevel(Profiler::LOG_OFF);
        
        $result = $this->profiler->getTaskerProfilerOutput(2);
        
        $this->assertNull($result);
    }

    public function testGetTaskerProfilerOutputWithLogAlways(): void
    {
        $tickerMock = $this->createMock(TickerAbstract::class);
        $tickerMock->method('getName')->willReturn('test_profiler');
        $tickerMock->method('getFormatted')->willReturn(['test' => 'data']);
        $tickerMock->method('getQueries')->willReturn([1 => ['query' => 'SELECT *']]);
        
        $this->profiler->addProfiler($tickerMock);
        $this->profiler->setLogLevel(Profiler::LOG_ALWAYS);
        
        $result = $this->profiler->getTaskerProfilerOutput(2, 0);
        
        $this->assertIsArray($result);
    }

    public function testGetTaskerProfilerOutputWithLogAboveThreshold(): void
    {
        $tickerMock = $this->createMock(TickerAbstract::class);
        $tickerMock->method('getName')->willReturn('test_profiler');
        $tickerMock->method('getFormatted')->willReturn(['test' => 'data']);
        $tickerMock->method('getQueries')->willReturn([1 => ['query' => 'SELECT *']]);
        
        $this->profiler->addProfiler($tickerMock);
        $this->profiler->setLogLevel(Profiler::LOG_ABOVE_THRESHOLD);
        $this->profiler->setThreshold(1000);
        
        $result = $this->profiler->getTaskerProfilerOutput(2, 1500);
        
        $this->assertIsArray($result);
    }

    public function testGetTaskerProfilerOutputWithErrorStatus(): void
    {
        $tickerMock = $this->createMock(TickerAbstract::class);
        $tickerMock->method('getName')->willReturn('test_profiler');
        $tickerMock->method('getFormatted')->willReturn(['test' => 'data']);
        $tickerMock->method('getQueries')->willReturn([1 => ['query' => 'SELECT *']]);
        
        $this->profiler->addProfiler($tickerMock);
        $this->profiler->setLogLevel(Profiler::LOG_ERRORS_ONLY);
        
        $result = $this->profiler->getTaskerProfilerOutput(1, 0);
        
        $this->assertIsArray($result);
    }

    public function testClearProfilers(): void
    {
        $tickerMock = $this->createMock(TickerAbstract::class);
        $tickerMock->expects($this->once())->method('clear');
        
        $this->profiler->addProfiler($tickerMock);
        $this->profiler->clearProfilers();
    }

    public function testGetProfilerSummary(): void
    {
        $tickerMock = $this->createMock(TickerAbstract::class);
        $tickerMock->method('getType')->willReturn('mysql');
        $tickerMock->method('getTotalNumQueries')->willReturn(10);
        $tickerMock->method('getTotalElapsedTime')->willReturn(0.5);
        
        $this->profiler->addProfiler($tickerMock);
        
        $result = $this->profiler->getProfilerSummary();
        
        $this->assertIsArray($result);
        $this->assertArrayHasKey('prof_mysql_calls', $result);
        $this->assertArrayHasKey('prof_mysql_time_ms', $result);
        $this->assertEquals(10, $result['prof_mysql_calls']);
        $this->assertEquals(500, $result['prof_mysql_time_ms']);
    }
}
