<?php

namespace G4\Runner\Presenter;

use G4\Http\Request as HttpRequest;
use G4\CleanCore\Request\Request;
use G4\CleanCore\Response\Response;
use G4\Runner\Profiler;

class DataTransfer
{

    private $httpRequest;

    private $httpResponse;

    private $profiler;

    private $request;

    private $response;


    public function __construct(HttpRequest $httpRequest, Profiler $profiler, Request $request, Response $response)
    {
        $this->httpRequest  = $httpRequest;
        $this->profiler     = $profiler;
        $this->request      = $request;
        $this->response     = $response;
    }

    /**
     * @return HttpRequest
     */
    public function getHttpRequest()
    {
        return $this->httpRequest;
    }

    /**
     * @return HttpResponse
     */
    public function getHttpResponse()
    {
        return $this->httpResponse;
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