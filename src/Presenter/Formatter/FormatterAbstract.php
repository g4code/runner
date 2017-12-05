<?php

namespace G4\Runner\Presenter\Formatter;

use G4\Constants\Override;
use G4\Runner\Presenter\DataTransfer;
use G4\Runner\Presenter\Formatter\FormatterInterface;

abstract class FormatterAbstract implements FormatterInterface
{

    /**
     * @var DataTransfer
     */
    private $dataTransfer;

    /**
     * @var array
     */
    private $formatted;


    public function __construct()
    {
        $this->formatted    = [];
    }

    public function getBasicData()
    {
        return [
            'code'     => $this->getDataTransfer()->getResponse()->getHttpResponseCode(),
            'message'  => $this->getDataTransfer()->getResponse()->getHttpMessage(),
            'response' => $this->getDataTransfer()->getResponse()->getResponseObject(),
            'env'      => defined(APPLICATION_ENV) ? APPLICATION_ENV : null,
        ];
    }

    /**
     * @return DataTransfer
     */
    public function getDataTransfer()
    {
        return $this->dataTransfer;
    }

    public function getProfilerData()
    {
        return $this->isProfilerEnabled()
            ? ['profiler' =>  $this->getDataTransfer()->getProfiler()->getProfilerOutput($this->getDataTransfer()->getResponse()->getHttpResponseCode())]
            : [];
    }

    public function setDataTransfer(DataTransfer $dataTransfer)
    {
        $this->dataTransfer = $dataTransfer;
        return $this;
    }

    private function isProfilerEnabled()
    {
        return $this->getDataTransfer()->getHttpRequest()->has(Override::DB_PROFILER)
            && $this->getDataTransfer()->getHttpRequest()->get(Override::DB_PROFILER) == 1;
    }
}