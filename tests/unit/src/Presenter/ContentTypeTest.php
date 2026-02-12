<?php

use G4\Runner\Presenter\DataTransfer;
use G4\Runner\Presenter\HeaderAccept;
use G4\Runner\Presenter\ContentType;
use G4\CleanCore\Response\Response;
use PHPUnit\Framework\TestCase;

class ContentTypeTest extends TestCase
{
    /**
     * @dataProvider validDataProvider
     *
     * @param string $format
     * @param string $contentType
     */
    public function testGetContentTypeIfResponseObjectPartIsNull($format, $contentType)
    {
        $dataTransfer = $this->createMock(DataTransfer::class);
        $headerAccept = $this->createMock(HeaderAccept::class);
        $response = $this->createMock(Response::class);

        $dataTransfer
            ->expects($this->once())
            ->method('getResponse')
            ->willReturn($response);

        $response
            ->expects($this->once())
            ->method('getResponseObjectPart')
            ->with('format')
            ->willReturn(null);

        $headerAccept
            ->expects($this->once())
            ->method('getFormat')
            ->willReturn($format);

        $obj = new ContentType($dataTransfer, $headerAccept);

        $result = $obj->getContentType();
        $this->assertEquals($contentType, $result);
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param string $format
     * @param string $contentType
     */
    public function testGetContentTypeIfResponseObjectPartIsNotNull($format, $contentType)
    {
        $dataTransfer = $this->createMock(DataTransfer::class);
        $headerAccept = $this->createMock(HeaderAccept::class);
        $response = $this->createMock(Response::class);

        $dataTransfer
            ->expects($this->once())
            ->method('getResponse')
            ->willReturn($response);

        $response
            ->expects($this->once())
            ->method('getResponseObjectPart')
            ->with('format')
            ->willReturn($format);

        $headerAccept
            ->expects($this->never())
            ->method('getFormat');

        $obj = new ContentType($dataTransfer, $headerAccept);

        $result = $obj->getContentType();
        $this->assertEquals($contentType, $result);
    }

    /**
     * @dataProvider invalidDataProvider
     *
     * @param string $format
     */
    public function testInvalidFormat($format)
    {
        $dataTransfer = $this->createMock(DataTransfer::class);
        $headerAccept = $this->createMock(HeaderAccept::class);
        $response = $this->createMock(Response::class);

        $dataTransfer
            ->expects($this->once())
            ->method('getResponse')
            ->willReturn($response);

        $response
            ->expects($this->once())
            ->method('getResponseObjectPart')
            ->with('format')
            ->willReturn($format);

        $headerAccept
            ->expects($this->never())
            ->method('getFormat');

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Not valid runner view format!');
        $this->expectExceptionCode(600);

        $obj = new ContentType($dataTransfer, $headerAccept);
        $obj->getContentType();
    }

    /**
     * Data provider with valid data
     *
     * @return array
     */
    public static function validDataProvider()
    {
        return [
            ['gif', 'image/gif'],
            ['twig', 'text/html; charset=UTF-8'],
            ['json', 'application/json; charset=UTF-8'],
        ];
    }

    /**
     * Data provider with invalid data
     *
     * @return array
     */
    public static function invalidDataProvider()
    {
        return [
            ['ppam'],
            ['mdb'],
        ];
    }
}
