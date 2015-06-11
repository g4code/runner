<?php

namespace G4\Runner;

use G4\Constants\Http;
use G4\Constants\Parameters;
use G4\Log\Logger as LogLogger;
use G4\Runner\Logger;
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
     * @var Logger
     */
    private $logger;

    /**
     * @var Profiler
     */
    private $profiler;

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
        $this->profiler = new Profiler();
        $this->logger   = new Logger();
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

    /**
     * @return \G4\Http\Request
     */
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

    public function registerRequestLogger(LogLogger $logger)
    {
        $this->logger->setLogger($logger);
        return $this;
    }

    //TODO: Drasko: refactor this!
    public final function run()
    {
        $this->route();
        $this->parseApplicationMethod();

        $this->application = new Application($this);

        $this->logger->logRequest($this->application);

        $this->application->run();

        (new Presenter
            (new DataTransfer(
                $this->getHttpRequest(),
                $this->profiler,
                $this->application->getRequest(),
                $this->application->getResponse())))
            ->render();

         $this->logger->logResponse($this->application, $this->profiler);
    }

    public function setCommando(\G4\Commando\Cli $commando)
    {
        $this->commando = $commando;
        return $this;
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