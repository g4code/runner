<?php

namespace G4\Runner\Exception;

use G4\Runner\Route\ErrorCodes;

class InvalidModule extends \Exception
{
    const MESSAGE = 'Module is not valid: %s';

    public function __construct($module)
    {
        parent::__construct(sprintf(self::MESSAGE, $module), ErrorCodes::INVALID_MODULE);
    }
}