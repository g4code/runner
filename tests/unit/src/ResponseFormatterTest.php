<?php

namespace G4\Runner;

use G4\Runner\Presenter\Formatter\FormatterInterface;
use G4\Runner\Presenter\Formatter\Basic;
use G4\Runner\Presenter\Formatter\Verbose;
use PHPUnit\Framework\TestCase;

class ResponseFormatterTest extends TestCase
{
    private ResponseFormatter $responseFormatter;

    protected function setUp(): void
    {
        $this->responseFormatter = new ResponseFormatter();
    }

    public function testConstructor(): void
    {
        $this->assertInstanceOf(ResponseFormatter::class, $this->responseFormatter);
    }

    public function testGetBasicReturnsDefaultBasicFormatter(): void
    {
        $result = $this->responseFormatter->getBasic();
        
        $this->assertInstanceOf(Basic::class, $result);
    }

    public function testGetVerboseReturnsDefaultVerboseFormatter(): void
    {
        $result = $this->responseFormatter->getVerbose();
        
        $this->assertInstanceOf(Verbose::class, $result);
    }

    public function testAddBasicSetsCustomFormatter(): void
    {
        $customFormatter = $this->createMock(FormatterInterface::class);
        $this->responseFormatter->addBasic($customFormatter);
        
        $result = $this->responseFormatter->getBasic();
        
        $this->assertSame($customFormatter, $result);
    }

    public function testAddVerboseSetsCustomFormatter(): void
    {
        $customFormatter = $this->createMock(FormatterInterface::class);
        $this->responseFormatter->addVerbose($customFormatter);
        
        $result = $this->responseFormatter->getVerbose();
        
        $this->assertSame($customFormatter, $result);
    }
}
