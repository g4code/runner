<?php

namespace G4\Runner\Presenter;

use G4\Constants\Format;
use G4\Runner\Presenter\DataTransfer;
use G4\Runner\Presenter\View\Json;
use G4\Runner\Presenter\View\Twig;
use G4\Runner\Presenter\View\ViewInterface;

class View
{

    /**
     * @var ContentType
     */
    private $contentType;

    /**
     * @var array
     */
    private $data;

    /**
     * @var DataTransfer
     */
    private $dataTransfer;


    public function __construct(array $data, ContentType $contentType, DataTransfer $dataTransfer)
    {
        $this->data         = $data;
        $this->contentType  = $contentType;
        $this->dataTransfer = $dataTransfer;
    }

    /**
     * @return string
     */
    public function renderBody()
    {
        return $this->getViewInstance()->renderBody();
    }

    /**
     * @return ViewInterface
     */
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