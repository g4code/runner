<?php

namespace G4\Runner\Presenter;

use Aura\Accept\Accept;
use Aura\Accept\AcceptFactory;
use Aura\Accept\Media\MediaValue;
use G4\Constants\Format;
use G4\Constants\HeaderAccept as HeaderAcceptConst;

class HeaderAccept
{

    /**
     * @var Accept
     */
    private $accept;

    /**
     * @var MediaValue
     */
    private $media;


    public function __construct()
    {
        $acceptFactory = new AcceptFactory($_SERVER);
        $this->accept = $acceptFactory->newInstance();
    }

    public function getFormat()
    {
        $this->media = $this->accept->negotiateMedia($this->available());
        return $this->hasDetected()
            ? $this->acceptMap()[$this->media->getValue()]
            : Format::JSON;
    }

    /**
     * @return array
     */
    private function acceptMap()
    {
        return [
            HeaderAcceptConst::JSON => Format::JSON,
            HeaderAcceptConst::HTML => Format::TWIG,
        ];
    }

    private function available()
    {
        return [
            HeaderAcceptConst::JSON,
            HeaderAcceptConst::HTML,
        ];
    }

    private function hasDetected()
    {
        return $this->media instanceof MediaValue;
    }
}