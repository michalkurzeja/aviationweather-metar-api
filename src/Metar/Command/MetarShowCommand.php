<?php

namespace Metar\Command;

use Metar\AviationWeather\AviationWeather;
use METAR\Message;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MetarShowCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('show')
            ->setDescription('Load and display METAR information for an airport')
            ->addArgument('code', InputArgument::REQUIRED, 'Airport code');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $code = $input->getArgument('code');

        $aviationWeather = new AviationWeather();
        $metar = $aviationWeather->getMetar($code);

        $this->outputWeather($output, $metar);
    }

    private function outputWeather(OutputInterface &$output, $metar)
    {
        $parsed = $this->parseMetar($metar);

        $output->writeln(sprintf('Showing weather conditions for <info>%s</info> airport:', $parsed->getLocation()));
        $output->writeln(sprintf('Hour: <info>%s</info> <comment>zulu</comment>', $parsed->getZuluTime(), $parsed->getDayOfMonth()));
        $output->writeln(sprintf('Temperature: <info>%s</info> <comment>C</comment>, dew point: <info>%s</info> <comment>C</comment>', $parsed->getTemperature(), $parsed->getDewPoint()));
        $output->writeln(sprintf('Wind: <info>%s</info> <comment>MPS</comment>, direction: <info>%s</info>', $parsed->getWindSpeed(), $parsed->getWindDirection()));
        $output->writeln(sprintf('Visibility: <info>%s</info>', $parsed->getVisibility()));
        $output->writeln(sprintf('Weather: <info>%s</info>', implode(',', $parsed->getWeather())));
    }

    private function parseMetar($metar)
    {
        $reporting = error_reporting(0);     // temporarily hide parse errors (for unsupported METAR options)

        $message = new Message($metar);

        error_reporting($reporting);

        return $message;
    }
}