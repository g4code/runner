<?php

namespace G4\Runner\Presenter;

use G4\Http\Request as HttpRequest;
use G4\Runner\Profiler;
use G4\CleanCore\Request\Request;
use G4\CleanCore\Response\Response;
use PHPUnit\Framework\TestCase;

class DataTransferTest extends TestCase
{
    private DataTransfer $dataTransfer;
    private HttpRequest $httpRequest;
    private Profiler $profiler;
    private Request $request;
    private Response $response;

    protected function setUp(): void
    {
        $this->httpRequest = $this->createMock(HttpRequest::class);
        $this->profiler = $this->createMock(Profiler::class);
        $this->request = $this->createMock(Request::class);
        $this->response = $this->createMock(Response::class);
        
        $this->dataTransfer = new DataTransfer(
            $this->httpRequest,
            $this->profiler,
            $this->request,
            $this->response,
            '1.0.0'
        );
    }

    public function testGetHttpRequest(): void
    {
        $result = $this->dataTransfer->getHttpRequest();
        
        $this->assertSame($this->httpRequest, $result);
    }

    public function testGetProfiler(): void
    {
        $result = $this->dataTransfer->getProfiler();
        
        $this->assertSame($this->profiler, $result);
    }

    public function testGetRequest(): void
    {
        $result = $this->dataTransfer->getRequest();
        
        $this->assertSame($this->request, $result);
    }

    public function testGetResponse(): void
    {
        $result = $this->dataTransfer->getResponse();
        
        $this->assertSame($this->response, $result);
    }

    public function testGetVersion(): void
    {
        $result = $this->dataTransfer->getVersion();
        
        $this->assertEquals('1.0.0', $result);
    }

    public function testGetVersionWithNullVersion(): void
    {
        $dataTransfer = new DataTransfer(
            $this->httpRequest,
            $this->profiler,
            $this->request,
            $this->response
        );
        
        $result = $dataTransfer->getVersion();
        
        $this->assertNull($result);
    }
}
