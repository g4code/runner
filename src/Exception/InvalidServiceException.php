<?php

namespace G4\Runner\Exception;

use G4\Runner\Route\ErrorCodes;

class InvalidServiceException extends \Exception
{
    const MESSAGE = 'Service is not valid: %s';

    public function __construct($service)
    {
        parent::__construct(sprintf(self::MESSAGE, $service), ErrorCodes::INVALID_SERVICE);
    }
}