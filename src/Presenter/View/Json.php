<?php

namespace G4\Runner\Presenter\View;

use G4\Constants\Override;
use G4\Runner\Presenter\DataTransfer;
use G4\Runner\Presenter\View\ViewInterface;

class Json implements ViewInterface
{

    private $data;

    private $dataTransfer;


    public function __construct($data, DataTransfer $dataTransfer)
    {
        $this->data         = $data;
        $this->dataTransfer = $dataTransfer;
    }

    public function renderBody()
    {
        return json_encode($this->data, $this->encodeOptions());
    }

    private function encodeOptions()
    {
        return ($this->dataTransfer->getHttpRequest()->has(Override::PRETTY_PRINT)
            && $this->dataTransfer->getHttpRequest()->get(Override::PRETTY_PRINT) == 1)
                ? JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT
                : JSON_UNESCAPED_UNICODE;
    }
}