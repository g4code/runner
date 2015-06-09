<?php

namespace G4\Runner\Presenter;

use G4\Constants\Format;
use G4\Constants\ContentType as ContentTypeConst;
use G4\Runner\Presenter\DataTransfer;

class ContentType
{

    /**
     * @var DataTransfer
     */
    private $dataTransfer;

    /**
     * @var string
     */
    private $format;


    /**
     * @param DataTransfer $dataTransfer
     */
    public function __construct(DataTransfer $dataTransfer)
    {
        $this->dataTransfer = $dataTransfer;
        $this->setFormat();
    }

    /**
     * @return string
     */
    public function getContentType()
    {
        return $this->map()[$this->getFormat()];
    }

    /**
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * @return boolean
     */
    private function isFormatValid()
    {
        return in_array($this->format, [
            Format::JSON,
            Format::TWIG
        ]);
    }

    /**
     * @return array
     */
    private function map()
    {
        return [
            Format::JSON => ContentTypeConst::JSON,
            Format::TWIG => ContentTypeConst::HTML,
        ];
    }

    /**
     * @throws \Exception
     */
    private function setFormat()
    {
        $format = $this->dataTransfer->getResponse()->getResponseObjectPart(Format::FORMAT);
        $this->format = $format === null ? Format::JSON : $format;
        if (! $this->isFormatValid()) {
            throw new \Exception(600, 'Not valid runner view format!');
        }
    }
}