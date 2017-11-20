<?php

namespace app\models;

use app\modules\admin\models\Eventlog;
use Yii;
use yii\base\Model;

//библиотека общих функций
class LibraryModel extends Model
{
    //определение реального IP юзера
    public static function GetRealIp()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP']))
        {
            $ip=$_SERVER['HTTP_CLIENT_IP'];
        }
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
        {
            $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        else
        {
            $ip=$_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    //геолокация
    public static function GeoLocation() {
        $ip = getenv('SERVER_ADDR');
        $url = 'http://api.sypexgeo.net/xml/'. $ip .'';
        $xml = simplexml_load_string(file_get_contents($url));
        $loc_array = array($xml->ip->city->lat,$xml->ip->city->lon);
        return $loc_array;
    }

    //выборка всех месяцев
    public static function GetMonths(){
        return array('01'=>'Январь','02'=>'Февраль','03'=>'Март','04'=>'Апрель','05'=>'Май','06'=>'Июнь','07'=>'Июль',
            '08'=>'Август','09'=>'Сентябрь','10'=>'Октябрь','11'=>'Ноябрь','12'=>'Декабрь',);
    }

    //возвращаем название месяца по номеру
    public static function SetMonth($id){
        $months = self::GetMonths();
        foreach ($months as $key=>$month){
            if($key == $id)
                return mb_strtolower($month,'UTF-8');
        }
    }

    public static function AddEventLog($type,$msg){
        $log = new Eventlog();
        $log->user_id = Yii::$app->user->identity->getId();
        $log->user_ip = self::GetRealIp();
        $log->type = $type;
        $log->is_read = 0;
        $log->msg = $msg;
        $log->save();
        return true;
    }
}