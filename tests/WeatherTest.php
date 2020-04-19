<?php

namespace Rufo\Weather\Tests;

use Rufo\Weather\Exceptions\InvalidArgumentException;
use Rufo\Weather\Weather;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Client;

class WeatherTest extends TestCase
{
    public function testGetWeather()
    {
        // json
        $response = new Response(200, [], '{"success": true}');
        $client = \Mockery::mock(Client::class);
        $client->allows()->get('https://restapi.amap.com/v3/weather/weatherInfo', [
            'query' => [
                'key' => 'mock-key',
                'city' => '深圳',
                'output' => 'json',
                'extensions' => 'base',
            ],
        ])->andReturn($response);

        $w = \Mockery::mock(Weather::class, ['mock-key'])->makePartial();
        $w->allows()->getHttpClient()->andReturn($client);
        $this->assertSame(['success' => true], $w->getWeather('深圳'));


        // xml
        $response = new Response(200, [], '<hello>content</hello>');
        $client = \Mockery::mock(Client::class);
        $client->allows()->get('https://restapi.amap.com/v3/weather/weatherInfo', [
            'query' => [
                'key' => 'mock-key',
                'city' => '深圳',
                'extensions' => 'all',
                'output' => 'xml',
            ],
        ])->andReturn($response);

        $w = \Mockery::mock(Weather::class, ['mock-key'])->makePartial();
        $w->allows()->getHttpClient()->andReturn($client);
        $this->assertSame('<hello>content</hello>', $w->getWeather('深圳', 'all', 'xml'));
    }


    /***
     * ./vendor/bin/phpunit --filter testGetWeatherWithInvalid
     * --filter 是指只测试方法 testGetWeatherWithInvalid 的前缀部分
     * @throws InvalidArgumentException
     * @throws \Rufo\Weather\Exceptions\HttpException
     */
    public function testGetWeatherWithInvalidType()
    {
        $w = new Weather('mock-key');
        // 断言会抛出此异常类
        $this->expectException(InvalidArgumentException::class);
        $w->getWeather('深圳', 'foo');
//        $this->fail('Failed to assert getWeather');
    }


}
