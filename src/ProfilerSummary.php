<?php

namespace G4\Runner;

use G4\Profiler\Ticker\TickerAbstract;

class ProfilerSummary
{
    const FORMATTER_PREFIX = 'prof_';
    const FORMATTER_SUFFIX_CALLS = '_calls';
    const FORMATTER_SUFFIX_TIME = '_time_ms';

    const CALLS = 'calls';
    const TIME = 'time';

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

        foreach ($this->profilers as $aProfiler) {
            /** @var TickerAbstract $aProfiler */
            $profilerType = $aProfiler->getType();
            $summary[$profilerType][self::CALLS] = isset($summary[$profilerType][self::CALLS])
                ? $summary[$profilerType][self::CALLS] + $aProfiler->getTotalNumQueries()
                : $aProfiler->getTotalNumQueries();

            $summary[$profilerType][self::TIME] = isset($summary[$profilerType][self::TIME])
                ? $summary[$profilerType][self::TIME] + $aProfiler->getTotalElapsedTime()
                : $aProfiler->getTotalElapsedTime();
        }

        $formatted = [];
        foreach ($summary as $type => $item) {
            $formatted[self::FORMATTER_PREFIX . $type . self::FORMATTER_SUFFIX_CALLS] = $item[self::CALLS];
            $formatted[self::FORMATTER_PREFIX . $type . self::FORMATTER_SUFFIX_TIME] = $item[self::TIME] * 1000;
        }
        return $formatted;
    }
}
