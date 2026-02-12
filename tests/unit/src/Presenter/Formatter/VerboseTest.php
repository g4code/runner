<?php

namespace G4\Runner\Presenter\Formatter;

use G4\Http\Request as HttpRequest;
use G4\Runner\Profiler;
use G4\Runner\Presenter\DataTransfer;
use G4\CleanCore\Request\Request;
use G4\CleanCore\Response\Response;
use PHPUnit\Framework\TestCase;

class VerboseTest extends TestCase
{
    private Verbose $formatter;
    private DataTransfer $dataTransfer;

    protected function setUp(): void
    {
        if (!defined('APPLICATION_ENV')) {
            define('APPLICATION_ENV', 'test');
        }
        
        $this->formatter = new Verbose();
        
        $httpRequest = $this->createMock(HttpRequest::class);
        $httpRequest->method('has')->willReturn(false);
        
        $profiler = $this->createMock(Profiler::class);
        $profiler->method('getProfilerOutput')->willReturn([]);
        
        $request = $this->createMock(Request::class);
        $request->method('getModule')->willReturn('test');
        $request->method('getResourceName')->willReturn('resource');
        $request->method('getMethod')->willReturn('get');
        $request->method('getParamsAnonymized')->willReturn(['param' => 'value']);
        
        $response = $this->createMock(Response::class);
        $response->method('getHttpResponseCode')->willReturn(200);
        $response->method('getHttpMessage')->willReturn('OK');
        $response->method('getResponseObject')->willReturn(['data' => 'test']);
        $response->method('getApplicationResponseCode')->willReturn(1000);
        $response->method('getResponseMessage')->willReturn('Success');
        
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
        $this->assertArrayHasKey('app_code', $result);
        $this->assertArrayHasKey('app_message', $result);
        $this->assertArrayHasKey('params', $result);
        $this->assertArrayHasKey('method', $result);
        $this->assertArrayHasKey('resource_name', $result);
        $this->assertArrayHasKey('body_id', $result);
        
        $this->assertEquals(200, $result['code']);
        $this->assertEquals('OK', $result['message']);
        $this->assertEquals(1000, $result['app_code']);
        $this->assertEquals('Success', $result['app_message']);
        $this->assertEquals('get', $result['method']);
        $this->assertEquals('resource', $result['resource_name']);
        $this->assertEquals('test_resource_get', $result['body_id']);
    }

    public function testSetDataTransfer(): void
    {
        $result = $this->formatter->setDataTransfer($this->dataTransfer);
        
        $this->assertInstanceOf(Verbose::class, $result);
    }
}
