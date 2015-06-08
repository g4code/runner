<?php

namespace G4\Runner;

use G4\CleanCore\Request\Request;
use G4\Constants\Parameters;

class Application extends \G4\CleanCore\Application
{
    public function __construct(\G4\Runner\RunnerInterface $appRunner = null)
    {
        if(null !== $appRunner && $appRunner instanceof \G4\Runner\RunnerInterface) {
            $request = new Request();
            $request
                ->setModule($appRunner->getApplicationModule())
                ->setMethod($appRunner->getApplicationMethod())
                ->setResourceName($appRunner->getApplicationService())
                ->setParams($appRunner->getApplicationParams());

            // set anonymization rules for sensitive parameters
            foreach ($this->getRequestAnonymizationRules() as $param => $rule) {
                $request->setAnonymizationRules($param, $rule);
            }

            $this
                ->setRequest($request)
                ->setAppNamespace($appRunner->getApplicationModule());
        }
    }

    private function getRequestAnonymizationRules()
    {
        return [
            Parameters::X_ND_AUTH => function($value) {
                return substr_replace($value, str_repeat('*', 20), 0, -12);
            },
            Parameters::X_ND_APP_KEY => function($value) {
                return substr_replace($value, str_repeat('*', 20), 0, -12);
            },
            Parameters::CC_NUMBER => function($value) {
                return substr_replace($value, str_repeat('*', 12), 0, -4);
            },
            Parameters::CC_CVV2 => '***',
            Parameters::SESSION => null, // null value will actually unset params key from response
            '__site_id'     => null,
            '__system_type' => null,
            'image'         => null,
        ];
    }
}