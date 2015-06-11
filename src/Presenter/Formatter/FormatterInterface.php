<?php

namespace G4\Runner\Presenter\Formatter;

use G4\Runner\Presenter\DataTransfer;

interface FormatterInterface
{

    public function format();

    public function setDataTransfer(DataTransfer $dataTransfer);
}