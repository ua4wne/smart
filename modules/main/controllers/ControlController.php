<?php

namespace app\modules\main\controllers;

use app\modules\main\models\Device;
use app\modules\main\models\Option;

class ControlController extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'controllers' => ['main/control'],
                    ],
                ]
            ],
        ];
    }

    public function actionIndex()
    {
        $uid = $_GET['device']; //выделяем UID устройства из запроса и смотрим, есть ли такой в базе
        $device = Device::findOne(['uid'=>$uid]);
        if(!empty($device)){ //есть такое устройство, парсим дальше, выбираем остальные параметры
            foreach($_GET as $key=>$value){
                if($key!='device'){
                    $option = Option::findOne(['device_id'=>$device->id,'alias'=>$key]);
                    $option->val = $value;
                    $option->save();
                }
            }
        }
        return true;
        //return $this->render('index');
    }

}
