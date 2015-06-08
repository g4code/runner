<?php

namespace G4\Runner\Presenter;

use G4\Constants\Http;
use G4\Http\Response as HttpResponse;
use G4\Runner\Presenter\ContentType;
use G4\Runner\Presenter\DataTransfer;

class Renderer
{

    private $contentType;

    private $body;

    private $dataTransfer;

    private $httpResponse;


    public function __construct($body, ContentType $contentType, DataTransfer $dataTransfer)
    {
        $this->body         = $body;
        $this->contentType  = $contentType;
        $this->dataTransfer = $dataTransfer;
    }

    public function render()
    {
        $this->getHttpResponse()
            ->setHttpResponseCode($this->httpResponseCode())
            ->setBody($this->body)
            ->setHeader('Content-Type', $this->contentType->getContentType(), true)
            ->sendHeaders()
            ->sendResponse();
    }

    private function getHttpResponse()
    {
        if (!$this->httpResponse instanceof HttpResponse) {
            $this->httpResponse = new HttpResponse();
        }
        return $this->httpResponse;
    }

    private function httpResponseCode()
    {
        return $this->dataTransfer->getResponse()->getHttpResponseCode() == Http::CODE_204
            ? Http::CODE_200
            : $this->dataTransfer->getResponse()->getHttpResponseCode();
    }
}