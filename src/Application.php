<?php

namespace G4\Runner;

use G4\CleanCore\Request\Request;
use G4\DI\Container as DI;

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

            // set anonymization rules for sensitive parameters
            if(DI::has('RequestAnonymizationRules')) {
                $anonymizationRules = DI::get('RequestAnonymizationRules');
                if(!empty($anonymizationRules)) {
                    foreach ($anonymizationRules as $param => $rule) {
                        $request->setAnonymizationRules($param, $rule);
                    }
                }
            }

            $this
                ->setRequest($request)
                ->setAppNamespace($appRunner->getApplicationModule());
        }
    }
}