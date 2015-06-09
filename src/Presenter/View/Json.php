<?php

namespace G4\Runner\Presenter\View;

use G4\Constants\Override;
use G4\Runner\Presenter\DataTransfer;
use G4\Runner\Presenter\View\ViewAbstract;
use G4\Runner\Presenter\View\ViewInterface;

class Json extends ViewAbstract implements ViewInterface
{

    /**
     * @return string
     */
    public function renderBody()
    {
        return json_encode($this->getData(), $this->encodeOptions());
    }

    /**
     * @return string
     */
    private function encodeOptions()
    {
        return ($this->getDataTransfer()->getHttpRequest()->has(Override::PRETTY_PRINT)
            && $this->getDataTransfer()->getHttpRequest()->get(Override::PRETTY_PRINT) == 1)
                ? JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT
                : JSON_UNESCAPED_UNICODE;
    }
}