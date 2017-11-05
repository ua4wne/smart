<?php

namespace app\commands;
use app\models\Weather;
use yii\console\Controller;
use app\modules\main\models\Config;

class CronController extends Controller
{
    //получение XML-файла с данными погоды
    public function actionGetWeather()
    {
        $api_key = Config::findOne(['param'=>'API_KEY_FORECAST'])->val;
        $city_id = Config::findOne(['param'=>'CITY_ID'])->val;
        $geoloc = Config::findOne(['param'=>'USE_GEOLOCATION'])->val;
        if(isset($api_key)&&isset($city_id)&&isset($geoloc)){
            $model = new Weather($api_key,$city_id,$geoloc);
            $model->Forecast();
        }
        else
            echo 'Error config';
    }

}
