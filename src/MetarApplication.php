<?php

use Metar\Command\MetarShowCommand;

class MetarApplication extends \Symfony\Component\Console\Application
{
    public function __construct()
    {
        parent::__construct('metar', '1.0');

        $this->add(new MetarShowCommand());
    }
}