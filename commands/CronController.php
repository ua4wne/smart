<?php

namespace app\commands;
use app\models\Weather;
use app\modules\main\models\Logger;
use app\modules\main\models\Option;
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

    //запись переменных в лог
    public function actionLogger(){
        //находим переменные, значения которых необходимо писать в лог
        $options = Option::find()->select(['id','val'])->where(['to_log'=>1])->all();
        foreach ($options as $option){
            $date = date('Y-m-d H:i:s');
            $new = $option->val;
            //находим последнее значение переменной в логе
            $logger = Logger::find()->where(['option_id'=>$option->id])->orderBy(['created_at' => SORT_DESC])->limit(1)->all();
            foreach ($logger as $log){
                $old = $log->val;
            }
            if(empty($logger)){
                //еще нет записей в логе, это первая
                $newlog = new Logger();
                $newlog->option_id = $option->id;
                $newlog->val = $option->val;
                $newlog->created_at = $date;
                $newlog->updated_at = $date;
                $newlog->save();
            }
            else{
                if($old != $new){
                    //значение параметра отличается от последнего сохраненного
                    $newlog = new Logger();
                    $newlog->option_id = $option->id;
                    $newlog->val = $option->val;
                    $newlog->created_at = $date;
                    $newlog->updated_at = $date;
                    $newlog->save();
                }
            }
        }
    }

}
