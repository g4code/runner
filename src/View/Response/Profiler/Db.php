<?php

namespace G4\Runner\View\Response\Profiler;

class Db
{

    /**
     * @var array
     */
    private $profilers;


    public function __construct(array $profilers = null)
    {
        $this->profilers = $profilers;
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