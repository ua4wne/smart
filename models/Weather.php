<?php
namespace app\models;

use Yii;
use yii\base\Model;
use app\models\LibraryModel;

class Weather extends Model {
    const ONE_DAY = 'weather'; //текущий прогноз
    const FIVE_DAYS = 'forecast'; //прогноз на пять дней
    const CACHE_LIFETIME = 7200; //время кэша файла в секундах, 3600=1 час
    const CACHE_FILE = DIRECTORY_SEPARATOR . 'temp' . DIRECTORY_SEPARATOR . 'forecast.xml'; // временный файл-кэш
    const CALVIN = 273.15; //для перевода из Кельвинов в Цельсии
    private $city_id; //город в формате Moscow,ru
    private $api_key; // = '360d7ad192fa3bf83b5df1e380570d24'; //http://openweathermap.org/forecast5
    private $url;
    private $geoloc; //использовать геолокацию для определения местоположения
    private $filename;

    function __construct($key,$city,$geoloc=false){
        //http://api.openweathermap.org/data/2.5/forecast?q=Tomilino,ru&mode=xml&APPID=360d7ad192fa3bf83b5df1e380570d24 прогноз на пять дней
        //http://api.openweathermap.org/data/2.5/weather?q=Tomilino,ru&mode=xml&APPID=360d7ad192fa3bf83b5df1e380570d24 текущий прогноз
        $this->api_key = $key;
        $this->geoloc = $geoloc;
        $this->city_id = $city;
    }

    public function Forecast(){
        //$webroot = Yii::$app->basePath; //::getPathOfAlias('webroot');
        $this->filename = @web . self::CACHE_FILE;
        if ( file_exists($this->filename) ) {
            $cache_modified = time() - @filemtime($this->filename);
            if ( $cache_modified > self::CACHE_LIFETIME ) {
                //обновляем файл погоды, если время файла кэша устарело
                $this->GetDataXML($this->filename);
            }
        }
        else {
            //если нет файла погоды вообще, закачиваем его
            $this->GetDataXML($this->filename);
        }
        //if(file_exists($this->filename))
        //    $data = simplexml_load_file($this->filename);
    }

    public static function GetContent($data){
        $content = array();
        $content['city'] = $data->location->name;
        $lat = $data->location->location[latitude];
        $lat = round((float)$lat,2);
        $lon = $data->location->location[longitude];
        $lon = round((float)$lon,2);
        $content['lat'] = $lat;
        $content['lon'] = $lon;
        $timestamp = strtotime(date('c'))-3*3600; //MSK -> UTC
        $utc = date('c',$timestamp); //текущее время в формате UTC
        $params = $data->forecast;
        foreach ($params as $param){
            $from = $param->time['from'];
            $to = $param->time['to'];
            if(strtotime($from)<strtotime($utc)&&strtotime($utc)<strtotime($to)) {
                $timestamp = strtotime($from)+3*3600;
                $from = date('d-m-Y H:i',$timestamp); //UTC -> MSK
                $timestamp = strtotime($to)+3*3600;
                $to = date('d-m-Y H:i',$timestamp); //UTC -> MSK
                $alt = $param->time->symbol[name];
                $icon = $param->time->symbol['var'];
                $content['img'] = '<img src="images/w/'.$icon.'.png" alt="'.$alt.'">';
                //echo 'wind-dir: '.$res->windDirection[deg].' '.$res->windDirection[code].' '.$res->windDirection[name].'<br>';
                $wind = self::getWindDirection($param->time->windDirection[code]).' '.$param->time->windSpeed[mps].' м\сек';
                //echo 'wind-speed: '.$res->windSpeed[name].' '.$res->windSpeed[mps].'<br>';
                //$tempr = $res->temperature[value].'<sup>o</sup>C';
                //$min_t = $res->temperature[min].'<sup>o</sup>C';
                $max_t = self::getTempSign($param->time->temperature[max]);
                $max_t .= ' <sup>o</sup>C';
                $content['max_t'] = $max_t;
                $press = round($param->time->pressure[value] * 0.750062,2);
                //echo 'pressure: '.$res->pressure[unit].' '.$res->pressure[value].'<br>';
                $humidity = $param->time->humidity[value].' '.$param->time->humidity[unit];
                //echo 'clouds: '.$res->clouds[value].' '.$res->clouds[all].' '.$res->clouds[unit].'<br>';
                $clouds = $param->time->clouds[all].' '.$param->time->clouds[unit];
                break;
            }
        }
        $timestamp = strtotime($data->sun[rise])+3*3600;
        $sunrise = date('H:i',$timestamp); //UTC -> MSK
        $timestamp = strtotime($data->sun[set])+3*3600;
        $sunset = date('H:i',$timestamp); //UTC -> MSK
        $html = '<div class="grid3">
                    <span class="grey"><i class="ace-icon fa fa-sun-o fa-fw blue"></i>&nbsp; Восход</span>
                    <p class="bigger pull-right">'.$sunrise.'</p>
                 </div>
                 <div class="grid3">
                    <span class="grey"><i class="ace-icon fa fa-moon-o fa-fw blue"></i>&nbsp; Закат</span>
                    <p class="bigger pull-right">'.$sunset.'</p>
                 </div>
                 <div class="grid3">
                    <span class="grey"><i class="ace-icon fa fa-flag-o fa-fw blue"></i>&nbsp; Ветер</span>
                    <p class="bigger pull-right">'.$wind.'</p>
                 </div>';
        $content['html'] = $html;
        return $content;
    }


    //запрос данных с сайта
    private function GetDataXML($fname) {
        //определяем строку запроса url
        if($this->geoloc) {
            $coord = LibraryModel::GeoLocation();
            $this->url = 'api.openweathermap.org/data/2.5/'.self::FIVE_DAYS.'?lat='.$coord[0].'&lon='.$coord[1].'&mode=xml&APPID='.$this->api_key; //api.openweathermap.org/data/2.5/forecast?lat=35&lon=139
        }
        else
            $this->url = 'api.openweathermap.org/data/2.5/'.self::FIVE_DAYS.'?q='.$this->city_id.'&mode=xml&APPID='.$this->api_key;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER , 1);
        $xml_response=curl_exec($ch);
        curl_close($ch);
        //$webroot = Yii::$app->basePath;
        $fname =  @web . self::CACHE_FILE;
        $fh = fopen($fname, 'w');
        fwrite($fh, $xml_response);
        fclose($fh);
    }

    // получаем знак температуры
    private function getTempSign($temp)
    {
        if($temp>100)
            $temp-=self::CALVIN;
        $temp = round((float)$temp,0,PHP_ROUND_HALF_UP);
        return $temp > 0 ? '+'.$temp : $temp;
    }

    // получаем направления ветра
    private function getWindDirection($wind)
    {
        $wind = (string)$wind;
        if(strlen($wind)==3)
            $wind = substr($wind,1,2);
        $wind_direction = array('S'=>'&#8593; ю','N'=>'&#8595; с','W'=>'&#8594; з','E'=>'&#8592; в','SW'=>'&#8599; юз','SE'=>'&#8598; юв','NW'=>'&#8600; сз','NE'=>'&#8601; св');
        return $wind_direction[$wind];
    }
}