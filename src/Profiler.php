<?php

namespace G4\Runner;

use G4\Profiler\Ticker\TickerAbstract;

class Profiler
{
    const LOG_OFF = 0;
    const LOG_ERRORS_ONLY = 1;
    const LOG_ALWAYS = 2;

    /**
     * @var array
     */
    private $formatted;

    /**
     * @var array
     */
    private $timeline;

    /**
     * @var array|TickerAbstract[]
     */
    private $profilers;

    /**
     * @var int
     */
    private $logLevel;

    /**
     * @var int
     */
    private $threshold;

    public function __construct()
    {
        $this->profilers = [];
        $this->logLevel = self::LOG_ALWAYS;
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

    public function clearProfilers()
    {
        foreach ($this->profilers as $aProfiler) {
            $aProfiler->clear();
        }
    }

    public function setLogLevel($logLevel)
    {
        $this->logLevel = (int) $logLevel;
        return $this;
    }

    public function setThreshold($threshold)
    {
        $this->threshold = (int) $threshold;
        return $this;
    }

    /**
     * @return array
     */
    public function getProfilerOutput($httpCode, $dbProfiler = 0, $responseElapsedTime = 0)
    {
        return $this->hasProfilers() && $this->shouldLogProfiler($httpCode, $responseElapsedTime)
            ? $this->getFormatted($dbProfiler, $responseElapsedTime)
            : [];
    }

    public function getProfilerSummary()
    {
        return (new ProfilerSummary($this->profilers))->getSummary();
    }

    private function shouldLogProfiler($httpCode, $responseElapsedTime)
    {
        if ($this->logLevel === self::LOG_OFF) {
            return false;
        }
        if ($this->logLevel === self::LOG_ALWAYS) {
            return true;
        }

        if($this->isRequestTresholdExceded($responseElapsedTime)){
            return true;
        }

        return self::LOG_ERRORS_ONLY && substr($httpCode, 0, 1) != 2;
    }

    public function isRequestTresholdExceded($responseElapsedTime)
    {
        return $this->threshold && $responseElapsedTime > $this->threshold;
    }

    /**
     * @return array
     */
    private function getFormatted($dbProfiler, $responseElapsedTime)
    {
        if (!$this->hasFormatted()) {
            $this->formatted = [];
            $this->timeline = [];
            foreach ($this->profilers as $profiler) {
                $this->formatted[$profiler->getName()] = $profiler->getFormatted();
                $this->timeline[] = $profiler->getQueries();
            }
        }

        if ((int) $dbProfiler === 1) {
            return $this->formatted;
        }

        if ((int) $dbProfiler === 2 || $this->isRequestTresholdExceded($responseElapsedTime)) {
            $timelineFormatted = [];
            foreach ($this->timeline as $queries) {
                foreach ($queries as $key => $query) {
                    $timelineFormatted[$key] = $query;
                }
            }
            ksort($timelineFormatted, SORT_NUMERIC);
            return $timelineFormatted;
        }
        return ['unsuported request parameter'];
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
