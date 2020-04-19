<?php

namespace Rufo\Weather;

use GuzzleHttp\Client;
use Rufo\Weather\Exceptions\HttpException;
use Rufo\Weather\Exceptions\InvalidArgumentException;

class Weather
{
    private $key;
    private $guzzleOptions=[];

    public function __construct($key)
    {
        $this->key = $key;
    }

    public function getHttpClient()
    {
        return new Client($this->guzzleOptions);
    }

    public function setGuzzleOptions(array $options)
    {
        $this->guzzleOptions = $options;
    }

    public function getWeather($city, $type = 'base', $format = 'json')
    {
        if (!\in_array(\strtolower($format), ['xml', 'json'])) {
            throw new InvalidArgumentException('Invalid response format: ' . $format);
        }
        if (!\in_array(\strtolower($type), ['base', 'all'])) {
            throw new InvalidArgumentException('Invalid type (base/all):');
        }
        $format = \strtolower($format);
        $type = \strtolower($type);

        $url = 'https://restapi.amap.com/v3/weather/weatherInfo';
        $query = array_filter([
            'key' => $this->key,
            'city' => $city,
            'output' => $format,
            'extensions' => $type,
        ]);
        try{
            $response = $this->getHttpClient()->get($url, [
                'query' => $query,
            ])->getBody()->getContents();
        }catch (\Exception $e){
            throw new HttpException($e->getMessage(), $e->getCode(), $e);
        }
        return 'json' === $format ? \json_decode($response) : $response;
    }
}



