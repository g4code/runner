<?php

namespace G4\Runner\Presenter\Formatter;

use G4\Http\Request as HttpRequest;
use G4\Runner\Profiler;
use G4\Runner\Presenter\DataTransfer;
use G4\CleanCore\Request\Request;
use G4\CleanCore\Response\Response;
use PHPUnit\Framework\TestCase;

class BasicTest extends TestCase
{
    private Basic $formatter;
    private DataTransfer $dataTransfer;

    protected function setUp(): void
    {
        if (!defined('APPLICATION_ENV')) {
            define('APPLICATION_ENV', 'test');
        }
        
        $this->formatter = new Basic();
        
        $httpRequest = $this->createMock(HttpRequest::class);
        $httpRequest->method('has')->willReturn(false);
        
        $profiler = $this->createMock(Profiler::class);
        $profiler->method('getProfilerOutput')->willReturn([]);
        
        $request = $this->createMock(Request::class);
        
        $response = $this->createMock(Response::class);
        $response->method('getHttpResponseCode')->willReturn(200);
        $response->method('getHttpMessage')->willReturn('OK');
        $response->method('getResponseObject')->willReturn(['data' => 'test']);
        
        $this->dataTransfer = new DataTransfer(
            $httpRequest,
            $profiler,
            $request,
            $response,
            '1.0.0'
        );
    }

    public function testFormat(): void
    {
        $this->formatter->setDataTransfer($this->dataTransfer);
        $result = $this->formatter->format();
        
        $this->assertIsArray($result);
        $this->assertArrayHasKey('code', $result);
        $this->assertArrayHasKey('message', $result);
        $this->assertArrayHasKey('response', $result);
        $this->assertEquals(200, $result['code']);
        $this->assertEquals('OK', $result['message']);
        $this->assertEquals(['data' => 'test'], $result['response']);
    }

    public function testSetDataTransfer(): void
    {
        $result = $this->formatter->setDataTransfer($this->dataTransfer);
        
        $this->assertInstanceOf(Basic::class, $result);
    }

    public function testGetBasicData(): void
    {
        $this->formatter->setDataTransfer($this->dataTransfer);
        $result = $this->formatter->getBasicData();
        
        $this->assertIsArray($result);
        $this->assertArrayHasKey('code', $result);
        $this->assertArrayHasKey('message', $result);
        $this->assertArrayHasKey('app_version', $result);
        $this->assertArrayHasKey('response', $result);
    }

    public function testGetProfilerData(): void
    {
        $this->formatter->setDataTransfer($this->dataTransfer);
        $result = $this->formatter->getProfilerData();
        
        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }
}
