<?php

namespace G4\Runner\Presenter;

use G4\Constants\Env;
use G4\Constants\Override;
use \G4\Runner\Presenter\DataTransfer;
use \G4\Runner\Presenter\Formatter\Basic;
use \G4\Runner\Presenter\Formatter\Verbose;

class Formatter
{

    /**
     * @var DataTransfer
     */
    private $dataTransfer;

    public function __construct(DataTransfer $dataTransfer)
    {
        $this->dataTransfer = $dataTransfer;
    }

    public function format()
    {
        return $this->getFormatterInstance()->format();
    }

    private function getFormatterInstance()
    {
        return $this->shouldFormatVerbose()
            ? new Verbose($this->dataTransfer)
            : new Basic($this->dataTransfer);
    }

    private function shouldFormatVerbose()
    {
        return ($this->dataTransfer->getHttpRequest()->has(Override::VERBOSE_RESPONSE)
            && $this->dataTransfer->getHttpRequest()->get(Override::VERBOSE_RESPONSE) == 1)
                || !in_array(APPLICATION_ENV, [Env::PRODUCTION, Env::BETA]);
    }
}