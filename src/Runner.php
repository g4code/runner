<?php

namespace G4\Runner;

use G4\CleanCore\Application;
use G4\DI\Container as DI;

abstract class Runner implements RunnerInterface
{
    /**
     * @var G4\Http\Request
     */
    protected $httpRequest;

    /**
     * @var G4\Http\Response
     */
    protected $httpResponse;

    public final function bootstrap()
    {
        $this->_bootstrap();
        return $this;
    }

    abstract protected function _bootstrap();

    public final function run()
    {
        $this->_run();
        return $this;
    }

    abstract protected function _run();

    public function getHttpRequest()
    {
        if(null === $this->httpRequest) {
            $this->httpRequest = DI::get('HttpRequest');
        }
        return $this->httpRequest;
    }

    public function getHttpResponse()
    {
        if(null === $this->httpResponse) {
            $this->httpResponse = DI::get('HttpResponse');
        }
        return $this->httpResponse;
    }

    public function getApplicationModule()
    {
    }

    public function getApplicationService()
    {
    }

    public function getApplicationMethod()
    {
    }

    public function getApplicationParams()
    {
    }

}