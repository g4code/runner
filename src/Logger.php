<?php

namespace G4\Runner;

use G4\CleanCore\Application;
use G4\Constants\Parameters;
use G4\Log\Logger as LogLogger;
use G4\Log\Data\Request as LoggerDataRequest;
use G4\Log\Data\Response as LoggerDataResponse;


class Logger
{

    /**
     * @var LogLogger
     */
    private $logger;

    /**
     * @var float
     */
    private $startTime;

    /**
     * @var string
     */
    private $uniqueId;


    public function __construct()
    {
        $this->startTime = microtime(true);
        $this->uniqueId  = md5(uniqid($this->startTime, true));
    }

    /**
     * @param LogLogger $logger
     */
    public function setLogger(LogLogger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param Application $application
     */
    public function logRequest(Application $application)
    {
        if ($this->isLoggerRegistered()) {
            register_shutdown_function([$this->logger, 'log'], $this->getDataForRequest($application));
        }
    }

    /**
     * @param Application $application
     * @param Profiler $profiler
     */
    public function logResponse(Application $application, Profiler $profiler)
    {
        if ($this->isLoggerRegistered()) {
            register_shutdown_function([$this->logger, 'logAppend'], $this->getDataForResponse($application, $profiler));
        }
    }

    /**
     * @param Application $application
     * @return LoggerDataRequest
     */
    private function getDataForRequest(Application $application)
    {
        $loggerData = new LoggerDataRequest();
        $loggerData
            ->setApplication($application)
            ->setId($this->uniqueId)
            ->setParamsToObfuscate([Parameters::CC_NUMBER, Parameters::CC_CVV2, 'image']);
        return $loggerData;
    }

    /**
     * @param Application $application
     * @param Profiler $profiler
     * @return LoggerDataResponse
     */
    private function getDataForResponse(Application $application, Profiler $profiler)
    {
        $loggerData = new LoggerDataResponse();
        $loggerData
            ->setApplication($application)
            ->setId($this->uniqueId)
            ->setStartTime($this->startTime)
            ->setProfiler($profiler);
        return $loggerData;
    }

    /**
     * @return boolean
     */
    private function isLoggerRegistered()
    {
        return $this->logger instanceof LogLogger;
    }
}
