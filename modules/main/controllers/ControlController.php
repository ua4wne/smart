<?php

namespace app\modules\main\controllers;

use app\modules\main\models\Config;
use app\modules\main\models\Device;
use app\modules\main\models\Option;
use app\modules\main\models\Rule;
use app\modules\main\models\Sms;
use app\modules\main\models\Syslog;
use Yii;

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
                    //проверяем на вхождение в диапазон min - max
                    if($value < $option->min_val){
                        //запись в лог
                        $syslog = new Syslog();
                        $syslog->created_at = date('Y-m-d H:i:s');
                        $syslog->type = 'error';
                        $syslog->msg = 'Значение параметра <strong>'. $option->name . ' (' . $option->device->name . ')</strong>  меньше минимально возможного! <span class="red">value=' . $value . ' min_value=' . $option->min_val . '</span>';
                        $syslog->save();
                    }
                    if($value > $option->max_val){
                        //запись в лог
                        $syslog = new Syslog();
                        $syslog->created_at = date('Y-m-d H:i:s');
                        $syslog->type = 'error';
                        $syslog->msg = 'Значение параметра <strong>'. $option->name . ' (' . $option->device->name . ')</strong>  больше максимально возможного! <span class="red">value=' . $value . ' max_value=' . $option->max_val . '</span>';
                        $syslog->save();
                    }
                    //ищем связанные правила
                    $rules = Rule::find()->where(['option_id'=>$option->id])->count();
                    if($rules) {
                        $location = $device->location->name;
                        $this->CheckRules($option,$location); //проверяем связанные правила
                    }
                }
            }
        }
        return true;
        //return $this->render('index');
    }

    private function CheckRules(Option $model, $location){
        $time_stamp = strtotime(date('Y-m-d H:i:s')); //получаем текущую метку времени
        //определяем получателей почты
        $resp = Config::findOne(['param'=>'CONTROL_E_MAIL'])->val;
        $resipients = explode(",",$resp);
        $ph = Config::findOne(['param'=>'CONTROL_PHONE'])->val;
        $phones = explode(",",$ph);
        //ищем связанные правила
        $rules = Rule::find()->where(['option_id'=>$model->id])->all();
        foreach ($rules as $rule){
            if(!empty($rule->runtime))
                $runtime = strtotime($rule->runtime); //получаем метку времени старта правила
            if($time_stamp < $runtime) continue; //время еще не вышло, пропуск правила
            if($model->val > $rule->val){
                if($rule->condition == 'more' && $rule->action == 'mail'){
                    foreach ($resipients as $resipient){
                        $msg = str_replace("#LOCATION#",$location,$rule->text);
                        $msg = str_replace("#VAL#",$model->val . $model->unit,$msg);
                        $this->SendMail($resipient, $msg);
                    }
                }
                if($rule->condition == 'more' && $rule->action == 'sms'){
                    foreach ($phones as $phone){
                        $msg = str_replace('#LOCATION#',$location,$rule->text);
                        $msg = str_replace('#VAL#',$model->val . $model->unit,$msg);
                        $this->SendSms($phone,$msg);
                    }
                }
                if($rule->condition == 'more' && $rule->action == 'cmd'){
                    $cmd = $rule->text;
                    $id_rule = $rule->id;
                    $this->RunCmd($cmd,$id_rule);
                }
                $runtime = $time_stamp + $rule->step;
                $rule->runtime = date( 'Y-m-d H:i:s' , $runtime);
                $rule->save();
            }
            elseif($model->val < $rule->val){
                if($rule->condition == 'less' && $rule->action == 'mail'){
                    foreach ($resipients as $resipient){
                        $msg = str_replace("#LOCATION#",$location,$rule->text);
                        $msg = str_replace("#VAL#",$model->val . $model->unit,$msg);
                        $this->SendMail($resipient, $msg);
                    }
                }
                if($rule->condition == 'less' && $rule->action == 'sms'){
                    foreach ($phones as $phone){
                        $msg = str_replace('#LOCATION#',$location,$rule->text);
                        $msg = str_replace('#VAL#',$model->val . $model->unit,$msg);
                        $this->SendSms($phone,$msg);
                    }
                }
                if($rule->condition == 'less' && $rule->action == 'cmd'){
                    $cmd = $rule->text;
                    $id_rule = $rule->id;
                    $this->RunCmd($cmd,$id_rule);
                }
                $runtime = $time_stamp + $rule->step;
                $rule->runtime = date( 'Y-m-d H:i:s' , $runtime);
                $rule->save();
            }
            elseif($rule->val == $model->val){
                if($rule->condition == 'equ' && $rule->action == 'mail'){
                    foreach ($resipients as $resipient){
                        $msg = str_replace("#LOCATION#",$location,$rule->text);
                        $msg = str_replace("#VAL#",$model->val . $model->unit,$msg);
                        $this->SendMail($resipient, $msg);
                    }
                }
                if($rule->condition == 'equ' && $rule->action == 'sms'){
                    foreach ($phones as $phone){
                        $msg = str_replace('#LOCATION#',$location,$rule->text);
                        $msg = str_replace('#VAL#',$model->val . $model->unit,$msg);
                        $this->SendSms($phone,$msg);
                    }
                }
                if($rule->condition == 'equ' && $rule->action == 'cmd'){
                    $cmd = $rule->text;
                    $id_rule = $rule->id;
                    $this->RunCmd($cmd,$id_rule);
                }
                $runtime = $time_stamp + $rule->step;
                $rule->runtime = date( 'Y-m-d H:i:s' , $runtime);
                $rule->save();
            }
            elseif($rule->val != $model->val){
                if($rule->condition == 'not' && $rule->action == 'mail'){
                    foreach ($resipients as $resipient){
                        $msg = str_replace("#LOCATION#",$location,$rule->text);
                        $msg = str_replace("#VAL#",$model->val . $model->unit,$msg);
                        $this->SendMail($resipient, $msg);
                    }
                }
                if($rule->condition == 'not' && $rule->action == 'sms'){
                    foreach ($phones as $phone){
                        $msg = str_replace('#LOCATION#',$location,$rule->text);
                        $msg = str_replace('#VAL#',$model->val . $model->unit,$msg);
                        $this->SendSms($phone,$msg);
                    }
                }
                if($rule->condition == 'not' && $rule->action == 'cmd'){
                    $cmd = $rule->text;
                    $id_rule = $rule->id;
                    $this->RunCmd($cmd,$id_rule);
                }
                $runtime = $time_stamp + $rule->step;
                $rule->runtime = date( 'Y-m-d H:i:s' , $runtime);
                $rule->save();
            }
        }
    }

    private function SendMail($to,$msg){

        $result = Yii::$app->mailer->compose()
            ->setFrom(Yii::$app->params['adminEmail'])
            ->setTo($to)
            ->setSubject(Yii::$app->name)
            ->setTextBody($msg)
            ->setHtmlBody($msg)
            ->send();
        if(!$result){
            //запись в лог
            $syslog = new Syslog();
            $syslog->created_at = date('Y-m-d H:i:s');
            $syslog->type = 'error';
            $syslog->msg = 'Возникла ошибка при отправке системного сообщения адресату <strong>'. $to .'</strong>';
            $syslog->save();
        }
    }

    private function SendSms($to,$msg){
        $model = new Sms();
        $model->phone = $to;
        $model->message = $msg;
        $cost = $model->GetCost();
        if($cost>0){
            $model->SendViaMail();
        }
        $model->SendSms();
        //запись в лог
        $syslog = new Syslog();
        $syslog->created_at = date('Y-m-d H:i:s');
        $syslog->type = 'sms';
        $syslog->msg = 'Адресату <strong>'. $to .'</strong> было отправлено СМС. Стоимость отправки - ' . $cost . ' руб.';
        $syslog->save();
    }

    private function RunCmd($cmd,$rule){
        //запись в лог
        $syslog = new Syslog();
        $syslog->created_at = date('Y-m-d H:i:s');
        $syslog->type = 'info';
        $syslog->msg = 'Запуск команды <strong>'. $cmd . '</strong> <a href="/main/rule/'.$rule.'">по правилу</a>';
        $syslog->save();
    }

}
