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
     * @var array
     */
    private $availableContentTypes;

    /**
     * @var MediaValue
     */
    private $media;


    public function __construct($availableContentTypes = null)
    {
        $server = $_SERVER;
        // Drasko: dont analize media type if a file extension exists in the path
        unset($server['REQUEST_URI']);

        $acceptFactory = new AcceptFactory($server);
        $this->accept = $acceptFactory->newInstance();
        $this->availableContentTypes = $availableContentTypes;
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
        return $this->availableContentTypes
            ? $this->availableContentTypes
            : [
                HeaderAcceptConst::JSON,
                HeaderAcceptConst::HTML,
            ];
    }

    private function hasDetected()
    {
        return $this->media instanceof MediaValue;
    }
}