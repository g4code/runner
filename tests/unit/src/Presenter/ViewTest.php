<?php

use G4\Runner\Presenter\DataTransfer;
use G4\Runner\Presenter\View;
use G4\Runner\Presenter\ContentType;
use G4\CleanCore\Response\Response;

class ViewTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var View
     */
    private $view;

    /**
     * @var ContentType
     */
    private $contentType;

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
     * Set up method
     */
    public function setUp()
    {
        $this->contentType = $this->createMock(ContentType::class);
        $this->dataTransfer = $this->createMock(DataTransfer::class);

        $this->view = new View($this->data, $this->contentType, $this->dataTransfer);
    }

    public function testRenderBodyIfFormatIsGif()
    {
        $content = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==';
        $response = $this->createMock(Response::class);

        $this->contentType
            ->expects($this->once())
            ->method('getFormat')
            ->willReturn('gif');

        $this->dataTransfer
            ->expects($this->once())
            ->method('getResponse')
            ->willReturn($response);

        $response
            ->expects($this->once())
            ->method('getResponseObjectPart')
            ->with('content')
            ->willReturn($content);

        $result = $this->view->renderBody();

        $this->assertEquals($content, $result);
    }
}
