<?php

namespace G4\Runner;

use G4\Runner\Presenter\Formatter\FormatterInterface;
use G4\Runner\Presenter\Formatter\Basic;
use G4\Runner\Presenter\Formatter\Verbose;

class ResponseFormatter
{

    private $basic;

    private $verbose;

    public function __construct()
    {
        $this->basic   = null;
        $this->verbose = null;
    }

    public function addBasic(FormatterInterface $formatter)
    {
        $this->basic = $formatter;
    }

    public function addVerbose(FormatterInterface $formatter)
    {
        $this->verbose = $formatter;
    }

    public function getBasic()
    {
        return $this->basic instanceof FormatterInterface
            ? $this->basic
            : new Basic();
    }

    public function getVerbose()
    {
        return $this->verbose instanceof FormatterInterface
            ? $this->verbose
            : new Verbose();
    }
}