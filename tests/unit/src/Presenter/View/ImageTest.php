<?php

use G4\Runner\Presenter\DataTransfer;
use G4\Runner\Presenter\View\Image;
use G4\CleanCore\Response\Response;
use PHPUnit\Framework\TestCase;

class ImageTest extends TestCase
{
    /**
     * @var Image
     */
    private $image;

    /**
     * @var array
     */
    private $data = [
        'foo' => 'bar'
    ];

    /**
     * @var DataTransfer
     */
    private $dataTransfer;

    /**
     * @var Response
     */
    private $response;

    /**
     * Set up method
     */
    public function setUp(): void
    {
        $this->dataTransfer = $this->createMock(DataTransfer::class);
        $this->response = $this->createMock(Response::class);

        $this->image = new Image($this->data, $this->dataTransfer);
    }

    public function testRenderBody()
    {
        $content = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==';

        $this->dataTransfer
            ->expects($this->once())
            ->method('getResponse')
            ->willReturn($this->response);

        $this->response
            ->expects($this->once())
            ->method('getResponseObjectPart')
            ->with('content')
            ->willReturn($content);

        $result = $this->image->renderBody();

        $this->assertEquals($content, $result);
    }
}
