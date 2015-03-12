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
     * @var \G4\DataMapper\Profiler\Search
     */
    private $searchProfiler;

    public function __construct()
    {
        $this->dbProfiler     = DI::get('db')->getProfiler();
        $this->searchProfiler = \G4\DataMapper\Profiler\Search::getInstance();
    }

    public function getProfilerOutput()
    {
        return [
            'database' => $this->getProfilerDbOutput(),
            'search'   => $this->getProfilerSearchOutput(),
        ];
    }

    private function formatElapsedTime($value)
    {
        return sprintf("%3f ms", $value * 1000);
    }

    private function getProfilerDbOutput()
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

    private function getProfilerSearchOutput()
    {
        $output = [
            'total_number_of_queries' => $this->searchProfiler->getTotalNumQueries(),
            'total_elapsed_time'      => $this->formatElapsedTime($this->searchProfiler->getTotalElapsedTime()),
        ];
        foreach($this->searchProfiler->getData() as $data) {
            $output['queries'][] = $data->getFormatted();
        }
        return $output;
    }
}