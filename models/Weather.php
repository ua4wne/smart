<?php
namespace app\models;

use Yii;
use yii\base\Model;

class Weather extends Model {
    private $city_id; //город в формате Moscow,ru
    const API_KEY = '360d7ad192fa3bf83b5df1e380570d24'; //http://openweathermap.org/forecast5
    const ONE_DAY = 'weather'; //текущий прогноз
    const FIVE_DAYS = 'forecast'; //прогноз на пять дней
    const CACHE_LIFETIME = 7200; //время кэша файла в секундах, 3600=1 час
    const CACHE_FILE = '/temp/weather/forecast.xml'; // временный файл-кэш
    const CALVIN = 273.15; //для перевода из Кельвинов в Цельсии
    private $url;
    private $geoloc = false; //использовать геолокацию для определения местоположения
    private $filename;
}