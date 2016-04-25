<?php

namespace G4\Runner\Presenter;

use G4\Constants\Env;
use G4\Constants\Override;
use G4\Runner\ResponseFormatter;
use G4\Runner\Presenter\DataTransfer;
use G4\Runner\Presenter\Formatter\FormatterInterface;

class Formatter
{

    /**
     * @var DataTransfer
     */
    private $dataTransfer;

    /**
     * @var ResponseFormatter
     */
    private $responseFormatter;

    /**
     * @param DataTransfer $dataTransfer
     */
    public function __construct(DataTransfer $dataTransfer, ResponseFormatter $responseFormatter)
    {
        $this->dataTransfer      = $dataTransfer;
        $this->responseFormatter = $responseFormatter;
    }

    /**
     * @return array
     */
    public function format()
    {
        return $this->getFormatterInstance()
            ->setDataTransfer($this->dataTransfer)
            ->format();
    }

    /**
     * @return FormatterInterface
     */
    private function getFormatterInstance()
    {
        return $this->shouldFormatVerbose()
            ? $this->responseFormatter->getVerbose()
            : $this->responseFormatter->getBasic();
    }

    /**
     * @return boolean
     */
    private function shouldFormatVerbose()
    {
        $overdide = $this->dataTransfer->getHttpRequest()->has(Override::VERBOSE_RESPONSE)
            && $this->dataTransfer->getHttpRequest()->get(Override::VERBOSE_RESPONSE) == 1;

        $debug = defined('DEBUG') && DEBUG;

        $env = defined('APPLICATION_ENV') && !in_array(APPLICATION_ENV, [Env::PRODUCTION, Env::BETA]);

        return $overdide || $debug || $env;
    }
}