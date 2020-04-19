<?php
require __DIR__ .'/vendor/autoload.php';
use Rufo\Weather\Weather;

$key = 'bb5e3bd493d1f29f52f9d8ee4bf47049';
$w=new Weather($key);
var_dump($w->getWeather('成都'));
echo $w->getWeather('深圳', 'base', 'XML');