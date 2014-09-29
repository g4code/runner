<?php

namespace G4\Runner\View;

use G4\Runner\Runner;
use G4\Runner\Application;
use G4\DI\Container as DI;
use G4\Constants\Http;
use G4\Constants\Parameters as Param;
use G4\Constants\Override;

class View implements ViewInterface
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
     * @var array
     */
    private $httpResponse;

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
        $this->httpResponse = $this->appRunner->getHttpResponse();
    }

    public function render()
    {
        $body = $this->getResponseBody();

        // second we need to process response code
        // Drasko: response timeout bug if response code is 204
        $response = $this->application->getResponse();
        $code = $response->getHttpResponseCode() == Http::CODE_204
            ? Http::CODE_200
            : $response->getHttpResponseCode();

        $this->httpResponse->setHttpResponseCode($code);

        $this->httpResponse->setBody($body);
        $this->httpResponse->setHeader('Content-Type', 'application/json;charset=UTF-8', true);
        $this->httpResponse->sendHeaders();
        $this->httpResponse->sendResponse();
    }

    private function getResponseBody()
    {
        // first we need to format response
        $responseOutput = $this->formatResponse();

        // check for pretty print parameter
        $encodeOptions = isset($this->httpParams[Override::PRETTY_PRINT]) && ($this->httpParams[Override::PRETTY_PRINT] == 1)
            ? JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT
            : JSON_UNESCAPED_UNICODE;

        return json_encode($responseOutput, $encodeOptions);
    }

    private function formatResponse()
    {
        $formatterClass = '\G4\Runner\View\Response\Formatter\Basic';

        if(DI::has('G4|Runner|View|View|overrideResponseFormatterClass')) {
            $class = DI::get('G4|Runner|View|View|overrideResponseFormatterClass');
            if(null !== $class) {
                $formatterClass = $class;
            }
        }

        $formatter = new $formatterClass($this->appRunner, $this->application);

        return $formatter->render();
    }
}
