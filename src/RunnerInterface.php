<?php

namespace G4\Runner;

interface RunnerInterface
{
    public function bootstrap();

    public function run();

    public function getHttpRequest();

    public function getApplicationModule();

    public function getApplicationService();

    public function getApplicationMethod();

    public function getApplicationParams();

}