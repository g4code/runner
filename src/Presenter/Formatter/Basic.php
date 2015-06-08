<?php

namespace G4\Runner\Presenter\Formatter;

use G4\Runner\Presenter\Formatter\FormatterAbstract;
use G4\Runner\Presenter\Formatter\FormatterInterface;

class Basic extends FormatterAbstract implements FormatterInterface
{

    public function format()
    {
        return $this->getBasicData()
            + $this->getProfilerData();
    }
}
