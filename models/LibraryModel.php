<?php

namespace app\models;

use app\modules\admin\models\Eventlog;
use app\modules\main\models\Config;
use app\modules\main\models\Option;
use app\modules\main\models\Rule;
use app\modules\main\models\Syslog;
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

    public static function CheckRules(Option $model, $location){
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
            if(($model->val > $rule->val) && $rule->condition == 'more'){
                if($rule->action == 'mail'){
                    foreach ($resipients as $resipient){
                        $msg = str_replace("#LOCATION#",$location,$rule->text);
                        $msg = str_replace("#VAL#",$model->val . $model->unit,$msg);
                        self::SendMail($resipient, $msg);
                    }
                }
                if($rule->action == 'sms'){
                    foreach ($phones as $phone){
                        $msg = str_replace('#LOCATION#',$location,$rule->text);
                        $msg = str_replace('#VAL#',$model->val . $model->unit,$msg);
                        self::SendSms($phone,$msg);
                    }
                }
                if($rule->action == 'cmd'){
                    $cmd = $rule->text;
                    $id_rule = $rule->id;
                    self::RunCmd($cmd,$id_rule);
                }
                $runtime = $time_stamp + $rule->step;
                $rule->runtime = date( 'Y-m-d H:i:s' , $runtime);
                $rule->save();
            }
            elseif(($model->val < $rule->val) && $rule->condition == 'less'){
                if($rule->action == 'mail'){
                    foreach ($resipients as $resipient){
                        $msg = str_replace("#LOCATION#",$location,$rule->text);
                        $msg = str_replace("#VAL#",$model->val . $model->unit,$msg);
                        self::SendMail($resipient, $msg);
                    }
                }
                if($rule->action == 'sms'){
                    foreach ($phones as $phone){
                        $msg = str_replace('#LOCATION#',$location,$rule->text);
                        $msg = str_replace('#VAL#',$model->val . $model->unit,$msg);
                        self::SendSms($phone,$msg);
                    }
                }
                if($rule->action == 'cmd'){
                    $cmd = $rule->text;
                    $id_rule = $rule->id;
                    self::RunCmd($cmd,$id_rule);
                }
                $runtime = $time_stamp + $rule->step;
                $rule->runtime = date( 'Y-m-d H:i:s' , $runtime);
                $rule->save();
            }
            elseif(($rule->val == $model->val) && $rule->condition == 'equ'){
                if($rule->action == 'mail'){
                    foreach ($resipients as $resipient){
                        $msg = str_replace("#LOCATION#",$location,$rule->text);
                        $msg = str_replace("#VAL#",$model->val . $model->unit,$msg);
                        self::SendMail($resipient, $msg);
                    }
                }
                if($rule->action == 'sms'){
                    foreach ($phones as $phone){
                        $msg = str_replace('#LOCATION#',$location,$rule->text);
                        $msg = str_replace('#VAL#',$model->val . $model->unit,$msg);
                        self::SendSms($phone,$msg);
                    }
                }
                if($rule->action == 'cmd'){
                    $cmd = $rule->text;
                    $id_rule = $rule->id;
                    self::RunCmd($cmd,$id_rule);
                }
                $runtime = $time_stamp + $rule->step;
                $rule->runtime = date( 'Y-m-d H:i:s' , $runtime);
                $rule->save();
            }
            elseif(($rule->val != $model->val) && $rule->condition == 'not'){
                if($rule->action == 'mail'){
                    foreach ($resipients as $resipient){
                        $msg = str_replace("#LOCATION#",$location,$rule->text);
                        $msg = str_replace("#VAL#",$model->val . $model->unit,$msg);
                        self::SendMail($resipient, $msg);
                    }
                }
                if($rule->action == 'sms'){
                    foreach ($phones as $phone){
                        $msg = str_replace('#LOCATION#',$location,$rule->text);
                        $msg = str_replace('#VAL#',$model->val . $model->unit,$msg);
                        self::SendSms($phone,$msg);
                    }
                }
                if($rule->action == 'cmd'){
                    $cmd = $rule->text;
                    $id_rule = $rule->id;
                    self::RunCmd($cmd,$id_rule);
                }
                $runtime = $time_stamp + $rule->step;
                $rule->runtime = date( 'Y-m-d H:i:s' , $runtime);
                $rule->save();
            }
        }
    }

    public static function SendMail($to,$msg){
        $result = Yii::$app->mailer->compose()
            ->setFrom(Yii::$app->params['adminEmail'])
            ->setTo($to)
            ->setSubject(Yii::$app->name)
            ->setTextBody($msg)
            ->setHtmlBody($msg)
            ->send();
        //запись в лог
        $syslog = new Syslog();
        if($result){
            $syslog->msg = $msg;
            $syslog->type = 'email';
        }
        else{
            $syslog->type = 'error';
            $syslog->msg = 'Возникла ошибка при отправке системного сообщения адресату <strong>'. $to .'</strong>';
        }
        $syslog->from = Yii::$app->params['adminEmail'];
        $syslog->to = $to;
        $syslog->is_new = 1;
        $syslog->created_at = date('Y-m-d H:i:s');
        $syslog->save();
    }

    public static function SendSms($to,$msg){
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
        $syslog->from = 'Система';
        $syslog->to = $to;
        $syslog->is_new = 1;
        $syslog->created_at = date('Y-m-d H:i:s');
        $syslog->type = 'sms';
        $syslog->msg = 'Адресату <strong>'. $to .'</strong> было отправлено СМС. Стоимость отправки - ' . $cost . ' руб.';
        $syslog->save();
    }

    public static function RunCmd($cmd,$rule){
        //запись в лог
        $syslog = new Syslog();
        $syslog->from = 'Система';
        $syslog->to = 'shell';
        $syslog->is_new = 1;
        $syslog->created_at = date('Y-m-d H:i:s');
        $syslog->type = 'exec';
        $syslog->msg = 'Запуск команды <strong>'. $cmd . '</strong> <a href="/main/rule/'.$rule.'">по правилу</a>';
        $syslog->save();
    }

}