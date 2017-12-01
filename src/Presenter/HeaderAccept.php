<?php

namespace G4\Runner\Presenter;

use Aura\Accept\Accept;
use Aura\Accept\AcceptFactory;
use Aura\Accept\Media\MediaValue;

class HeaderAccept
{
    const FORMAT_HTML = 'html';
    const FORMAT_JSON = 'json';
    const FORMAT_TWIG = 'twig';
    const FORMAT_XML  = 'xml';

    const ACCEPT_HTML = 'text/html';
    const ACCEPT_TEXT = 'text/*';
    const ACCEPT_JSON = 'application/json';

    const ACCEPT_MAP = [
        self::ACCEPT_JSON => self::FORMAT_JSON,
        self::ACCEPT_HTML => self::FORMAT_TWIG,
        self::ACCEPT_TEXT => self::FORMAT_TWIG,
    ];

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


    /**
     * HeaderAccept constructor.
     * @param null $availableContentTypes
     */
    public function __construct($availableContentTypes = null)
    {
        $server = $_SERVER;
        // Drasko: dont analize media type if a file extension exists in the path
        unset($server['REQUEST_URI']);

        $acceptFactory                  = new AcceptFactory($server);
        $this->accept                   = $acceptFactory->newInstance();
        $this->availableContentTypes    = $availableContentTypes;
        $this->media                    = null;
    }

    /**
     * @return string
     */
    public function getFormat()
    {
        $this->media = $this->accept->negotiateMedia($this->available());

        return $this->hasDetected() && $this->existInAcceptMap()
            ? $this->getFormatFromAcceptMap()
            : self::FORMAT_JSON;
    }

    /**
     * @return bool
     */
    private function existInAcceptMap()
    {
        return array_key_exists($this->media->getValue(), self::ACCEPT_MAP);
    }

    /**
     * @return array
     */
    private function available()
    {
        return is_array($this->availableContentTypes)
            ? $this->availableContentTypes
            : [
                self::ACCEPT_JSON,
                self::ACCEPT_HTML,
            ];
    }

    /**
     * @return bool
     */
    private function hasDetected()
    {
        return $this->media instanceof MediaValue;
    }

    /**
     * @return string
     */
    private function getFormatFromAcceptMap()
    {
        return self::ACCEPT_MAP[$this->media->getValue()];
    }
}