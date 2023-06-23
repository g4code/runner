<?php

namespace G4\Runner\Presenter;

use G4\Http\Request as HttpRequest;
use G4\Runner\Profiler;
use G4\CleanCore\Request\Request;
use G4\CleanCore\Response\Response;

class DataTransfer
{

    /**
     * @var HttpRequest
     */
    private $httpRequest;

    /**
     * @var Profiler
     */
    private $profiler;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var Response
     */
    private $response;

    /**
     * @var string|null
     */
    private $version;


    public function __construct(
        HttpRequest $httpRequest,
        Profiler $profiler,
        Request $request,
        Response $response,
        string $version = null
    ){
        $this->httpRequest  = $httpRequest;
        $this->profiler     = $profiler;
        $this->request      = $request;
        $this->response     = $response;
        $this->version      = $version;
    }

    /**
     * @return string|null
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @return HttpRequest
     */
    public function getHttpRequest()
    {
        return $this->httpRequest;
    }

    /**
     * @return Profiler
     */
    public function getProfiler()
    {
        return $this->profiler;
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @return Response
     */
    public function getResponse()
    {
        return $this->response;
    }
}
