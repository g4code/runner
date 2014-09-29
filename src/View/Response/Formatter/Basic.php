<?php

namespace G4\Runner\View\Response\Formatter;

use G4\Constants\Override;
use G4\Runner\View\Response\Profiler\Db as DbProfiler;

class Basic
{
    /**
     * @var \G4\Runner\RunnerInterface
     */
    private $appRunner;

    /**
     * @var \G4\CleanCore\Application
     */
    private $application;

    /**
     * @var array
     */
    private $httpParams;

    /**
     *
     * @param \G4\Runner\RunnerInterface $appRunner
     * @param \G4\CleanCore\Application $application
     */
    public function __construct(\G4\Runner\RunnerInterface $appRunner, \G4\CleanCore\Application $application)
    {
        $this->appRunner   = $appRunner;
        $this->application = $application;

        $this->httpParams   = $this->appRunner->getHttpRequest()->getParams();
    }

    public function render()
    {
        $data = [
            'code'     => $this->getApplication()->getResponse()->getHttpResponseCode(),
            'message'  => $this->getApplication()->getResponse()->getHttpMessage(),
            'response' => $this->getApplication()->getResponse()->getResponseObject(),
        ];

        if($this->isDbProfilerEnabled()) {
            $dbProfiler = new DbProfiler;
            $data['profiler_db'] = $dbProfiler->getProfilerOutput();
        }

        return $data;
    }

    private function isDbProfilerEnabled()
    {
        return isset($this->httpParams[Override::DB_PROFILER]) && $this->httpParams[Override::DB_PROFILER] == 1;
    }

    public function getApplication()
    {
        return $this->application;
    }

    public function getAppRunner()
    {
        return $this->appRunner;
    }
}
