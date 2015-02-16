<?php
namespace Metar\AviationWeather;

use GuzzleHttp\ClientInterface;
use RuntimeException;
use Symfony\Component\DomCrawler\Crawler;

class AviationWeather
{
    const METAR_URL = 'http://www.aviationweather.gov/adds/metars?chk_metars=on&hoursStr=most+recent+only&station_ids=%s';

    /** @var ClientInterface */
    private $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    function getMetar($code)
    {
        $response = $this->client->get(sprintf(self::METAR_URL, $code));

        if (!$response || $response->getStatusCode() >= 400) throw new RuntimeException("Received {$response->getStatusCode()} response from the server!");

        $crawler = new Crawler($response->getBody()->getContents());
        $metar = $crawler->filter('td > font')->text();

        if (!$metar) throw new RuntimeException('Got empty result processing the dataset!');

        return $metar;
    }
}