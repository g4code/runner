<?php

namespace G4\Runner\Route;

use G4\Runner\Exception\InvalidModule;
use G4\Runner\Exception\InvalidModuleException;
use G4\Runner\Exception\InvalidService;
use G4\Runner\Exception\InvalidServiceException;
use G4\ValueObject\StringLiteral;

class Route
{
    /** @var StringLiteral */
    private $module;

    /** @var StringLiteral */
    private $service;

    /**
     * Route constructor.
     * @param StringLiteral $module
     * @param StringLiteral $service
     * @throws \Exception
     */
    public function __construct(StringLiteral $module, StringLiteral $service)
    {
        $this->setModule((string) $module);
        $this->setService((string) $service);
    }

    /**
     * @return string
     */
    public function module()
    {
        return $this->module;
    }

    /**
     * @return string
     */
    public function service()
    {
        return $this->service;
    }

    /**
     * @param $service
     * @throws \Exception
     */
    private function setService($service)
    {
        if (!preg_match('/^[A-Za-z0-9-]+$/', $service)) {
            throw new InvalidServiceException($service);
        }

        $this->service = $service;
    }

    /**
     * @param $module
     * @throws \Exception
     */
    private function setModule($module)
    {
        if (!preg_match('/^[A-Za-z0-9-]+$/', $module)) {
            throw new InvalidModuleException($module);
        }

        $this->module = $module;
    }
}