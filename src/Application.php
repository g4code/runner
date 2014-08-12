<?php

namespace G4\Runner;

use G4\CleanCore\Request\Request;

class Application extends \G4\CleanCore\Application
{
    public function __construct(\G4\Runner\Runner $appRunner = null)
    {
        if(null !== $appRunner && $appRunner instanceof \G4\Runner\Runner) {
            $request = new Request();
            $request
                ->setMethod($appRunner->getApplicationMethod())
                ->setResourceName($appRunner->getApplicationService())
                ->setParams($appRunner->getApplicationParams());

            $this
                ->setRequest($request)
                ->setAppNamespace($appRunner->getApplicationModule());
        }
    }
}