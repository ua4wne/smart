<?php

namespace app\models;

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
        $ip = $_SERVER['REMOTE_ADDR'];
        $url = 'http://api.sypexgeo.net/xml/'. $ip .'';
        $xml = simplexml_load_string(file_get_contents($url));
        $loc_array = array($xml->ip->city->lat,$xml->ip->city->lon);
        return $loc_array;
    }
}