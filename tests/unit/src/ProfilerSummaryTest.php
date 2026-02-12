<?php

namespace G4\Runner;

use G4\Profiler\Ticker\TickerAbstract;
use PHPUnit\Framework\TestCase;

class ProfilerSummaryTest extends TestCase
{
    public function testGetSummaryWithEmptyProfilers(): void
    {
        $profilerSummary = new ProfilerSummary([]);
        $result = $profilerSummary->getSummary();
        
        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    public function testGetSummaryWithSingleProfiler(): void
    {
        $tickerMock = $this->createMock(TickerAbstract::class);
        $tickerMock->method('getType')->willReturn('mysql');
        $tickerMock->method('getTotalNumQueries')->willReturn(10);
        $tickerMock->method('getTotalElapsedTime')->willReturn(0.5);
        
        $profilerSummary = new ProfilerSummary([$tickerMock]);
        $result = $profilerSummary->getSummary();
        
        $this->assertIsArray($result);
        $this->assertArrayHasKey('prof_mysql_calls', $result);
        $this->assertArrayHasKey('prof_mysql_time_ms', $result);
        $this->assertEquals(10, $result['prof_mysql_calls']);
        $this->assertEquals(500, $result['prof_mysql_time_ms']);
    }

    public function testGetSummaryWithMultipleProfilersOfSameType(): void
    {
        $tickerMock1 = $this->createMock(TickerAbstract::class);
        $tickerMock1->method('getType')->willReturn('mysql');
        $tickerMock1->method('getTotalNumQueries')->willReturn(10);
        $tickerMock1->method('getTotalElapsedTime')->willReturn(0.5);
        
        $tickerMock2 = $this->createMock(TickerAbstract::class);
        $tickerMock2->method('getType')->willReturn('mysql');
        $tickerMock2->method('getTotalNumQueries')->willReturn(5);
        $tickerMock2->method('getTotalElapsedTime')->willReturn(0.3);
        
        $profilerSummary = new ProfilerSummary([$tickerMock1, $tickerMock2]);
        $result = $profilerSummary->getSummary();
        
        $this->assertIsArray($result);
        $this->assertArrayHasKey('prof_mysql_calls', $result);
        $this->assertArrayHasKey('prof_mysql_time_ms', $result);
        $this->assertEquals(15, $result['prof_mysql_calls']);
        $this->assertEquals(800, $result['prof_mysql_time_ms']);
    }

    public function testGetSummaryWithMultipleProfilersOfDifferentTypes(): void
    {
        $tickerMock1 = $this->createMock(TickerAbstract::class);
        $tickerMock1->method('getType')->willReturn('mysql');
        $tickerMock1->method('getTotalNumQueries')->willReturn(10);
        $tickerMock1->method('getTotalElapsedTime')->willReturn(0.5);
        
        $tickerMock2 = $this->createMock(TickerAbstract::class);
        $tickerMock2->method('getType')->willReturn('redis');
        $tickerMock2->method('getTotalNumQueries')->willReturn(20);
        $tickerMock2->method('getTotalElapsedTime')->willReturn(0.2);
        
        $profilerSummary = new ProfilerSummary([$tickerMock1, $tickerMock2]);
        $result = $profilerSummary->getSummary();
        
        $this->assertIsArray($result);
        $this->assertArrayHasKey('prof_mysql_calls', $result);
        $this->assertArrayHasKey('prof_mysql_time_ms', $result);
        $this->assertArrayHasKey('prof_redis_calls', $result);
        $this->assertArrayHasKey('prof_redis_time_ms', $result);
        $this->assertEquals(10, $result['prof_mysql_calls']);
        $this->assertEquals(500, $result['prof_mysql_time_ms']);
        $this->assertEquals(20, $result['prof_redis_calls']);
        $this->assertEquals(200, $result['prof_redis_time_ms']);
    }

    public function testGetSummaryFormatsTimeInMilliseconds(): void
    {
        $tickerMock = $this->createMock(TickerAbstract::class);
        $tickerMock->method('getType')->willReturn('mysql');
        $tickerMock->method('getTotalNumQueries')->willReturn(1);
        $tickerMock->method('getTotalElapsedTime')->willReturn(1.234);
        
        $profilerSummary = new ProfilerSummary([$tickerMock]);
        $result = $profilerSummary->getSummary();
        
        $this->assertEquals(1234, $result['prof_mysql_time_ms']);
    }
}
