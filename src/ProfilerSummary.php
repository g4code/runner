<?php

namespace G4\Runner;

use G4\Profiler\Ticker\TickerAbstract;

class ProfilerSummary
{
    const FORMATTER_PREFIX = 'prof_';
    const FORMATTER_SUFFIX_CALLS = '_calls';
    const FORMATTER_SUFFIX_TIME = '_time_ms';

    /**
     * @var array|TickerAbstract[]
     */
    private $profilers;

    /**
     * @param $profilers
     */
    public function __construct(array $profilers)
    {
        $this->profilers = $profilers;
    }

    /**
     * @return array
     */
    public function getSummary()
    {
        $summary = [];

        foreach($this->profilers as $aProfiler) {
            /** @var TickerAbstract $aProfiler */
            $profilerType = $aProfiler->getType();
            $summary[$profilerType]['calls'] = isset($summary[$profilerType]['calls'])
                ? $summary[$profilerType]['calls'] + $aProfiler->getTotalNumQueries()
                : $aProfiler->getTotalNumQueries();

            $summary[$profilerType]['time_ms'] = isset($summary[$profilerType]['calls'])
                ? $summary[$aProfiler->getType()]['time_ms'] + $aProfiler->getTotalElapsedTime()
                : $aProfiler->getTotalElapsedTime();
        }

        $formatted = [];
        foreach ($summary as $type => $item) {
            $formatted[self::FORMATTER_PREFIX . $type . self::FORMATTER_SUFFIX_CALLS] = $item['calls'];
            $formatted[self::FORMATTER_PREFIX . $type . self::FORMATTER_SUFFIX_TIME] = $item['time_ms'] * 1000;
        }
        return $formatted;
    }
}
