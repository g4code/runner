<?php

namespace G4\Runner\Presenter\View;

use G4\Http\Request as HttpRequest;
use G4\Runner\Profiler;
use G4\Runner\Presenter\DataTransfer;
use G4\Runner\Presenter\ContentType;
use G4\CleanCore\Request\Request;
use G4\CleanCore\Response\Response;
use PHPUnit\Framework\TestCase;

class JsonTest extends TestCase
{
    private Json $jsonView;
    private DataTransfer $dataTransfer;
    private ContentType $contentType;

    protected function setUp(): void
    {
        $httpRequest = $this->createMock(HttpRequest::class);
        $httpRequest->method('has')->willReturn(false);
        
        $profiler = $this->createMock(Profiler::class);
        $request = $this->createMock(Request::class);
        $response = $this->createMock(Response::class);
        
        $this->dataTransfer = new DataTransfer(
            $httpRequest,
            $profiler,
            $request,
            $response
        );
        
        $this->contentType = $this->createMock(ContentType::class);
        
        $data = ['test' => 'data', 'number' => 123];
        $this->jsonView = new Json($data, $this->dataTransfer);
    }

    public function testRenderBody(): void
    {
        $result = $this->jsonView->renderBody();
        
        $this->assertIsString($result);
        $this->assertJson($result);
        
        $decoded = json_decode($result, true);
        $this->assertEquals('data', $decoded['test']);
        $this->assertEquals(123, $decoded['number']);
    }

    public function testRenderBodyWithPrettyPrint(): void
    {
        $httpRequest = $this->createMock(HttpRequest::class);
        $httpRequest->expects($this->once())
            ->method('has')
            ->with('__pretty')
            ->willReturn(true);
        $httpRequest->expects($this->once())
            ->method('get')
            ->with('__pretty')
            ->willReturn(1);
        
        $profiler = $this->createMock(Profiler::class);
        $request = $this->createMock(Request::class);
        $response = $this->createMock(Response::class);
        
        $dataTransfer = new DataTransfer(
            $httpRequest,
            $profiler,
            $request,
            $response
        );
        
        $data = ['test' => 'data'];
        $jsonView = new Json($data, $dataTransfer);
        
        $result = $jsonView->renderBody();
        
        $this->assertIsString($result);
        $this->assertJson($result);
        // Verify it's valid JSON and contains the data
        $decoded = json_decode($result, true);
        $this->assertEquals('data', $decoded['test']);
    }

    public function testRenderBodyWithUnicodeCharacters(): void
    {
        $data = ['text' => 'Ћирилица'];
        $jsonView = new Json($data, $this->dataTransfer);
        
        $result = $jsonView->renderBody();
        
        $this->assertIsString($result);
        $this->assertJson($result);
        $this->assertStringContainsString('Ћирилица', $result);
    }
}
