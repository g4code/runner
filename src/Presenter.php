<?php

namespace G4\Runner;

use G4\Constants\Format;
use G4\Runner\Presenter\DataTransfer;
use G4\Runner\Presenter\Formatter;
use G4\Runner\Presenter\View;
use G4\Runner\Presenter\Renderer;
use G4\Runner\Presenter\ContentType;
use G4\Runner\ResponseFormatter;
use G4\Constants\HeaderAccept;

class Presenter
{

    /**
     * @var DataTransfer
     */
    private $dataTransfer;

    /**
     * @var ContentType
     */
    private $contentType;

    /**
     * @var ResponseFormatter
     */
    private $responseFormatter;


    public function __construct(ResponseFormatter $responseFormatter, ContentType $contentType)
    {
        $this->dataTransfer      = $contentType->getDataTransfer();
        $this->responseFormatter = $responseFormatter;
        $this->contentType       = $contentType;
    }

    public function render()
    {
        (new Renderer($this->getFormattedBody(), $this->contentType, $this->dataTransfer))->render();
    }

    /**
     * @return string
     */
    private function getFormattedBody()
    {
        return (new View($this->getFormattedData(), $this->contentType, $this->dataTransfer))->renderBody();
    }

    /**
     * @return array
     */
    private function getFormattedData()
    {
        return (new Formatter($this->dataTransfer, $this->responseFormatter))->format();
    }
}
