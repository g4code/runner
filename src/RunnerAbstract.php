<?php

namespace G4\Runner;

use G4\Constants\Http;
use G4\Constants\Parameters;
use G4\Runner\Presenter\DataTransfer;
use G4\Runner\Profiler;

abstract class RunnerAbstract implements RunnerInterface
{

    /**
     * @var \G4\CleanCore\Application
     */
    private $application;

    /**
     * @var string
     */
    private $applicationMethod;

    /**
     * @var \G4\Http\Request
     */
    private $httpRequest;

    /**
     * @var \G4\Runner\Profiler
     */
    private $profiler;

    /**
     * @var \G4\Log\Logger
     */
    private $requestLogger;

    /**
     * @var string
     */
    private $uniqueId;

    /**
     * @var float
     */
    private $startTime;

    /**
     * @var \G4\Commando\Cli
     */
    private $commando;

    /**
     * @var array
     */
    private $routerOptions;



    public function __construct()
    {
        $this->uniqueId  = md5(uniqid(microtime(), true));
        $this->startTime = microtime(true);
        $this->profiler  = new Profiler();
    }

    public function getApplicationMethod()
    {
        return $this->applicationMethod;
    }

    public function getApplicationModule()
    {
        return ucwords($this->routerOptions['module']);
    }

    public function getApplicationParams()
    {
        return $this->getHttpRequest()->isCli()
            ? json_decode($this->commando->value('params'), true)
            : $this->getHttpRequest()->getParams();
    }

    public function getApplicationService()
    {
        return ucwords($this->routerOptions['service']);
    }

    public function getHttpRequest()
    {
        if(null === $this->httpRequest) {
            $this->httpRequest = new \G4\Http\Request();
        }
        return $this->httpRequest;
    }

    public function registerProfilerTicker(\G4\Profiler\Ticker\TickerAbstract $profiler)
    {
        $this->profiler->addProfiler($profiler);
        return $this;
    }

    public function registerRequestLogger(\G4\Log\Logger $logger)
    {
        $this->requestLogger = $logger;
        return $this;
    }

    //TODO: Drasko: refactor this!
    public final function run()
    {
        $this
            ->route()
            ->parseApplicationMethod();

        $this->application = new Application($this);

        $this->logRequest();

        $this->application->run();

        (new Presenter
            (new DataTransfer(
                $this->getHttpRequest(),
                $this->profiler,
                $this->application->getRequest(),
                $this->application->getResponse())))
            ->render();

        $this->logResponse();
    }

    public function setCommando(\G4\Commando\Cli $commando)
    {
        $this->commando = $commando;
        return $this;
    }

    //TODO: Drasko: Extract to new Logger class!
    private function isRequestLoggerRegistered()
    {
        return $this->requestLogger instanceof \G4\Log\Logger;
    }

    //TODO: Drasko: Extract to new Logger class!
    private function logResponse()
    {
        if ($this->isRequestLoggerRegistered()) {
            $loggerData = new \G4\Profiler\Data\Response();
            $loggerData
                ->setApplication($this->application)
                ->setId($this->uniqueId)
                ->setStartTime($this->startTime)
                ->setProfiler($this->getProfiler());
            register_shutdown_function([$this->requestLogger, 'logAppend'], $loggerData);
        }
    }

    //TODO: Drasko: Extract to new Logger class!
    private function logRequest()
    {
        if ($this->isRequestLoggerRegistered()) {
            $loggerData = new \G4\Profiler\Data\Request();
            $loggerData
                ->setApplication($this->application)
                ->setId($this->uniqueId)
                ->setParamsToObfuscate([Parameters::CC_NUMBER, Parameters::CC_CVV2, 'image']);
            register_shutdown_function([$this->requestLogger, 'log'], $loggerData);
        }
    }

    private function parseApplicationMethod()
    {
        if ($this->getHttpRequest()->isCli()) {
            $params = $this->commando->has('params')
                ? json_decode($this->commando->value('params'), true)
                : [];
            $method = $this->commando->has('method')
                ? strtoupper($this->commando->value('method'))
                : null;
            $id     = isset($params[Parameters::ID])
                ? $params[Parameters::ID]
                : null;
        } else {
            $method = $this->getHttpRequest()->getMethod();
            $id     = $this->getHttpRequest()->getParam(Parameters::ID);
        }

        $this->applicationMethod = ($method == Http::METHOD_GET && empty($id))
            ? 'Index'
            : ucwords(strtolower($method));
        return $this;
    }

    private function route()
    {
        $this->routerOptions = !$this->getHttpRequest()->isCli()
            ? require_once PATH_CONFIG . '/routes.php'
            : [
                'module'  => $this->commando->value('module'),
                'service' => $this->commando->value('service'),
            ];
        return $this;
    }
}