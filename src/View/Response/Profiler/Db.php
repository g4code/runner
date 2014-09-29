<?php

namespace G4\Runner\View\Response\Profiler;

use G4\DI\Container as DI;

class Db
{
    /** @var \Zend_Db_Profiler */
    private $dbProfiler;

    public function __construct()
    {
        $this->dbProfiler = DI::get('db')->getProfiler();
    }

    public function getProfilerOutput()
    {
        $output                  = [];
        $longestQuery            = '';
        $longestQueryElapsedTime = 0;

        $output['total_number_of_queries'] = $this->dbProfiler->getTotalNumQueries();
        $output['total_elapsed_time']      = $this->formatElapsedTime($this->dbProfiler->getTotalElapsedSecs());

        if ($this->dbProfiler->getTotalNumQueries()) {
            foreach ($this->dbProfiler->getQueryProfiles() as $queryProfile) {
                if ($queryProfile->getElapsedSecs() > $longestQueryElapsedTime) {
                    $longestQuery            = $queryProfile->getQuery();
                    $longestQueryElapsedTime = $queryProfile->getElapsedSecs();
                }

                $output['queries'][] = array(
                    'elapsed_time' => $this->formatElapsedTime($queryProfile->getElapsedSecs()),
                    'query'        => $queryProfile->getQuery()
                );
            }

            $output['longest_query'] = array(
                'elapsed_time' => $this->formatElapsedTime($longestQueryElapsedTime),
                'query'        => $longestQuery
            );
        }
        return $output;
    }

    private function formatElapsedTime($value)
    {
        return sprintf("%3f ms", $value * 1000);
    }
}