<?php

namespace G4\Runner\Presenter\View;

use G4\Runner\Presenter\DataTransfer;

abstract class ViewAbstract
{

    /**
     * @var array
     */
    private $data;

    /**
     * @var DataTransfer
     */
    private $dataTransfer;


    /**
     * @param array $data
     * @param DataTransfer $dataTransfer
     */
    public function __construct(array $data, DataTransfer $dataTransfer)
    {
        $this->data         = $data;
        $this->dataTransfer = $dataTransfer;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return DataTransfer
     */
    public function getDataTransfer()
    {
        return $this->dataTransfer;
    }
}