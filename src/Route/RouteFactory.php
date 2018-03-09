<?php

namespace G4\Runner\Route;

use G4\Factory\FromArrayAbstract;
use G4\Factory\ReconstituteInterface;
use G4\ValueObject\StringLiteral;

class RouteFactory extends FromArrayAbstract implements ReconstituteInterface
{
    /**
     * @return Route
     */
    public function reconstitute()
    {
        return new Route(
            new StringLiteral($this->get(RouteConstants::MODULE)),
            new StringLiteral($this->get(RouteConstants::SERVICE))
        );
    }
}