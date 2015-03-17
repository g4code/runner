<?php

namespace G4\Runner\View\Response\Profiler;

use G4\DI\Container as DI;

class Db
{
    /**
     * @var \Zend_Db_Profiler
     */
    private $dbProfiler;

    /**
     * @var array
     */
    private $profilers;


    public function __construct(array $profilers = null)
    {
        $this->profilers = $profilers;
        $this->dbProfiler = DI::get('db')->getProfiler();
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
        $output = [];
        foreach($this->profilers as $profiler) {
            $output[$profiler->getName()] = $profiler->getFormatted();
        }
        return $output;
    }

    /**
     * @return boolean
     */
    private function hasProfilers()
    {
        return !empty($this->profilers);
    }
}