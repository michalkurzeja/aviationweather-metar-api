<?php
namespace Metar\AviationWeather;

use GuzzleHttp\Client;
use RuntimeException;
use Symfony\Component\DomCrawler\Crawler;

class AviationWeather
{
    const URL = 'http://www.aviationweather.gov/adds/metars?chk_metars=on&hoursStr=most+recent+only&station_ids=%s';

    function getMetar($code)
    {
        $client = new Client();
        $response = $client->get(sprintf(self::URL, $code));

        if (!$response || $response->getStatusCode() >= 400) throw new RuntimeException("Received {$response->getStatusCode()} response from the server!");

        $crawler = new Crawler($response->getBody()->getContents());
        $metar = $crawler->filter('td > font')->text();

        if (!$metar) throw new RuntimeException('Got empty result processing the dataset!');

        return $metar;
    }
}