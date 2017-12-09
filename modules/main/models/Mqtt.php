<?php
/**
 * Created by PhpStorm.
 * User: dervish
 * Date: 08.12.2017
 * Time: 19:57
 */

namespace app\modules\main\models;

use yii\base\Model;

class Mqtt extends Model
{
    public $server;
    public $port;
    public $login;
    public $pass;
    public $ptopic;
    public $pval;
    public $stopic;
    public $untopic;

    public function rules()
    {
        return [
            [['server', 'port', 'login' , 'pass'], 'required'],
            [['port'], 'number', 'max' => 99999],
            [['port'], 'default', 'value' => '1883'],
            [['pval'], 'string', 'min' => 1, 'max' => 15],
            [['login', 'pass'], 'string', 'min' => 4, 'max' => 15],
            [['ptopic','stopic','untopic'], 'string', 'max' => 250],
            [['server'], 'string', 'min' => 7, 'max' => 32],
        ];
    }

    public function attributeLabels()
    {
        return [
            'server' => 'MQTT сервер',
            'port' => 'Порт',
            'login' => 'Логин',
            'pass' => 'Пароль',
            'ptopic' => 'Publish topic',
            'pval' =>'Publish value',
            'stopic' => 'Subscribe topic',
            'untopic' => 'Unsubscribe topic',
        ];
    }
}