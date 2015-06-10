<?php

namespace G4\Runner;

use G4\Profiler\Ticker\TickerAbstract;

class Profiler
{

    /**
     * @var array
     */
    private $formatted;

    /**
     * @var array
     */
    private $profilers;


    public function __construct()
    {
        $this->profilers = [];
        $this->formatted = null;
    }

    /**
     * @param TickerAbstract $profiler
     * @return Profiler
     */
    public function addProfiler(TickerAbstract $profiler)
    {
        $this->profilers[] = $profiler;
        return $this;
    }

    /**
     * @return array
     */
    public function getProfilerOutput()
    {
        return $this->hasProfilers()
            ? $this->getFormatted()
            : [];
    }

    /**
     * @return array
     */
    private function getFormatted()
    {
        if (!$this->hasFormatted()) {
            $this->formatted = [];
            foreach($this->profilers as $profiler) {
                $this->formatted[$profiler->getName()] = $profiler->getFormatted();
            }
        }
        return $this->formatted;
    }

    /**
     * @return boolean
     */
    private function hasFormatted()
    {
        return $this->formatted !== null;
    }

    /**
     * @return boolean
     */
    private function hasProfilers()
    {
        return !empty($this->profilers);
    }
}