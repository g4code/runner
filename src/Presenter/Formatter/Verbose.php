<?php

namespace G4\Runner\Presenter\Formatter;

use G4\Runner\Presenter\Formatter\FormatterAbstract;
use G4\Runner\Presenter\Formatter\FormatterInterface;

class Verbose extends FormatterAbstract implements FormatterInterface
{
    /**
     * @return array
     */
    public function format()
    {
        return $this->getBasicData()
            + $this->getVerboseData()
            + $this->getProfilerData();
    }

    private function getBodyId()
    {
        return join("_", [
            $this->getDataTransfer()->getRequest()->getMethod(),
            $this->getDataTransfer()->getRequest()->getResourceName(),
            $this->getDataTransfer()->getRequest()->getMethod(),
        ]);
    }

    private function getVerboseData()
    {
        return [
            'app_code'      => $this->getDataTransfer()->getResponse()->getApplicationResponseCode(),
            'app_message'   => $this->getDataTransfer()->getResponse()->getResponseMessage(),
            'params'        => $this->getDataTransfer()->getRequest()->getParamsAnonymized(),
            'method'        => $this->getDataTransfer()->getRequest()->getMethod(),
            'resource_name' => $this->getDataTransfer()->getRequest()->getResourceName(),
            'body_id'       => $this->getBodyId(),
        ];
    }
}
