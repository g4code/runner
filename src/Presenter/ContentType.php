<?php

namespace G4\Runner\Presenter;

use G4\Constants\Format;
use G4\Constants\ContentType as ContentTypeConst;
use G4\Runner\Presenter\DataTransfer;
use G4\Runner\Presenter\HeaderAccept;

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
     * @var HeaderAccept
     */
    private $headerAccept;


    /**
     * @param DataTransfer $dataTransfer
     */
    public function __construct(DataTransfer $dataTransfer)
    {
        $this->dataTransfer = $dataTransfer;
        $this->headerAccept = new HeaderAccept(); //TODO: Drasko move from construct dependency!
        $this->formatFactory();
    }

    /**
     * @return string
     */
    public function getContentType()
    {
        return $this->contentTypeMap()[$this->getFormat()];
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
            Format::TWIG,
        ]);
    }

    /**
     * @return array
     */
    private function contentTypeMap()
    {
        return [
            Format::JSON => ContentTypeConst::JSON,
            Format::TWIG => ContentTypeConst::HTML,
        ];
    }

    /**
     * @throws \Exception
     */
    private function formatFactory()
    {
        $format = $this->dataTransfer->getResponse()->getResponseObjectPart(Format::FORMAT);
        $this->format = $format === null ? $this->headerAccept->getFormat() : $format;
        if (! $this->isFormatValid()) {
            throw new \Exception(600, 'Not valid runner view format!');
        }
    }
}