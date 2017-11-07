<?php

namespace app\modules\main\controllers;

use app\models\LibraryModel;
use app\modules\main\models\CounterLog;
use app\modules\main\models\Device;
use yii\web\Controller;

class RegistrationController extends Controller
{
    public function actionIndex()
    {
        //$model = new CounterLog();
        //$counters = $this->GetCounters();
        //$select = array();
        //$month = LibraryModel::GetMonths();

        //$smonth = date("m");
        $year = date('Y');
        /*if(strlen($smonth)==1)
            $smonth.='0';
        foreach($counters as $count) {
            $select[$count['id']] = $count['name'].' ('.$count['text'].')'; //массив для заполнения данных в select формы
        }*/
        //выводим графики по всем счетчикам за год
        return $this->render('index');
    }

    //выборка всех счетчиков
    private function GetCounters(){
        return Device::find()->select(['id','name','descr'])->orderBy('name', SORT_ASC)->asArray()->all();
    }


}
