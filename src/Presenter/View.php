<?php

namespace G4\Runner\Presenter;

use G4\Constants\Format;
use G4\Runner\Presenter\DataTransfer;
use G4\Runner\Presenter\View\Json;
use G4\Runner\Presenter\View\Twig;

class View
{

    private $contentType;

    private $data;

    private $dataTransfer;

    private $format;


    public function __construct($data, ContentType $contentType, DataTransfer $dataTransfer)
    {
        $this->data         = $data;
        $this->contentType  = $contentType;
        $this->dataTransfer = $dataTransfer;
    }

    public function renderBody()
    {
        return $this->getViewInstance()->renderBody();
    }

    private function getViewInstance()
    {
        switch ($this->contentType->getFormat()) {
            case Format::TWIG:
                return new Twig($this->data, $this->dataTransfer);
            case Format::JSON:
            default:
                return new Json($this->data, $this->dataTransfer);
        }
    }
}