<?php

namespace G4\Runner;

use G4\Constants\Http;
use G4\Constants\Parameters;

abstract class RunnerAbstract implements RunnerInterface
{
    /**
     * @var G4\Http\Request
     */
    protected $httpRequest;

    /**
     * @var G4\Http\Response
     */
    protected $httpResponse;

    /**
     * @var array
     */
    private $profilers;

    /**
     * @var \G4\Log\Logger
     */
    private $requestLogger;


    protected $uniqueId;

    protected $startTime;

    /**
     * @var \G4\Commando\Cli
     */
    private $commando;

    protected $routerOptions;

    protected $applicationMethod;

    protected $view;

    /**
     * @var \G4\CleanCore\Application
     */
    protected $app;


    public function __construct()
    {
        $this->uniqueId  = md5(uniqid(microtime(), true));
        $this->startTime = microtime(true);
    }

    public function setCommando(\G4\Commando\Cli $commando)
    {
        $this->commando = $commando;
        return $this;
    }

    public function getProfilers()
    {
        return $this->profilers;
    }

    public function registerProfiler(\G4\Profiler\Ticker\TickerAbstract $profiler)
    {
        $this->profilers[] = $profiler;
        return $this;
    }

    public function registerRequestLogger(\G4\Log\Logger $logger)
    {
        $this->requestLogger = $logger;
        return $this;
    }

    public final function run()
    {
        $this
            ->route()
            ->parseApplicationMethod();

        $this->app = new Application($this);

        $this->logRequest();

        $this->app->run();

        $this->view = new \G4\Runner\View\View($this, $this->app);
        $this->view->render();

        $this->logResponse();
    }

    public function getHttpRequest()
    {
        if(null === $this->httpRequest) {
            $this->httpRequest = new \G4\Http\Request();
        }
        return $this->httpRequest;
    }

    public function getHttpResponse()
    {
        if(null === $this->httpResponse) {
            $this->httpResponse = new \G4\Http\Response();
        }
        return $this->httpResponse;
    }

    protected function parseApplicationMethod()
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

    protected function route()
    {
        $this->routerOptions = !$this->getHttpRequest()->isCli()
            ? require_once PATH_CONFIG . '/routes.php'
            : [
                'module'  => $this->commando->value('module'),
                'service' => $this->commando->value('service'),
            ];
        return $this;
    }

    public function getApplicationModule()
    {
        return ucwords($this->routerOptions['module']);
    }

    public function getApplicationService()
    {
        return ucwords($this->routerOptions['service']);
    }

    public function getApplicationMethod()
    {
        return $this->applicationMethod;
    }

    public function getApplicationParams()
    {
        return $this->getHttpRequest()->isCli()
            ? json_decode($this->commando->value('params'), true)
            : $this->getHttpRequest()->getParams();
    }

    private function logResponse()
    {
        if ($this->isRequestLoggerRegistered()) {
            $loggerData = new \G4\Profiler\Data\Response();
            $loggerData
                ->setApplication($this->app)
                ->setId($this->uniqueId)
                ->setStartTime($this->startTime);
            register_shutdown_function([$this->requestLogger, 'logAppend'], $loggerData);
        }
    }

    private function logRequest()
    {
        if ($this->isRequestLoggerRegistered()) {
            $loggerData = new \G4\Profiler\Data\Request();
            $loggerData
                ->setApplication($this->app)
                ->setId($this->uniqueId)
                ->setParamsToObfuscate([Parameters::CC_NUMBER, Parameters::CC_CVV2, 'image']);
            register_shutdown_function([$this->requestLogger, 'log'], $loggerData);
        }
    }

    private function isRequestLoggerRegistered()
    {
        return $this->requestLogger instanceof \G4\Log\Logger;
    }

}