<?php

namespace app\modules\main\controllers;

use app\models\LibraryModel;
use app\modules\main\models\CounterLog;
use app\modules\main\models\Device;
use app\modules\main\models\DeviceType;
use yii\data\SqlDataProvider;
use yii\web\Controller;
use Yii;

class RegistrationController extends Controller
{
    const NOT_VAL = 0; //нет значений
    const MORE_VAL = 1; //предыдущее значение больше текущего
    const LESS_VAL = 2; //предыдущее значение меньше текущего
    private $previous; //предыдущее показание счетчика

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
        $dataProvider1 = new SqlDataProvider([
            'sql' =>  'select d.name as name, sum(l.delta) as delta, sum(l.price) as price from counter_log l
                        inner join device d on d.id=l.device_id
                        where _year='.$year.' group by _month',
        ]);

        $dataProvider2 = new SqlDataProvider([
            'sql' =>  'select d.name as name, l.delta as delta from counter_log l
                        inner join device d on d.id=l.device_id
                        where _year='.$year.' order by _month',
        ]);

        $content = CounterLog::StatCounter($year);

        return $this->render('index',[
            'dataProvider1' => $dataProvider1,
            'dataProvider2' => $dataProvider2,
            'content' => $content,
        ]);
    }

    // Всплывшее модальное окно заполняем представлением формы с полями
    public function actionShowModal()
    {
        $month = LibraryModel::GetMonths();
        $smonth = date("m");
        if(strlen($smonth)==1)
            $smonth.='0';
        $year = date('Y');
        $rows = $this->GetCounters();
        $selcnt = array();
        foreach ($rows as $row){
            $selcnt[$row[id]] = $row[name];
        }
        //return 'OK';
        $model = new CounterLog();
        return $this->renderPartial('_form', [
            'model' => $model,
            'year' => $year,
            'selcnt' => $selcnt,
            'month' => $month,
            'smonth' => $smonth,
        ]);
    }

    //сохраняем данные из модального окна
    public function actionAddModal(){
        $model = new CounterLog();
        if($model->load(Yii::$app->request->post())){
            $result = $this->CheckCountVal($model->device_id,$model->val,$model->_year,$model->_month);
            //удаляем, если имеется запись за текущий месяц, чтобы не было дублей
            //try{CounterLog::deleteAll(['device_id'=>$model->device_id,'year'=>$model->_year,'month'=>$model->_month]);}
            //catch (\mysqli_sql_exception $e){}

            if($result===self::LESS_VAL)
                $model->delta = $model->val - $this->previous;
            else
                $model->delta = $model->val; //первая запись или замена счетчика
            $model->koeff = $model->device_id->tarif->koeff;
            $model->price = $model->delta * $model->koeff;
            //$model->save();
            return 'OK';
        }
        else
            return 'ERR';
    }

    //выборка всех счетчиков
    private function GetCounters(){
        $type = DeviceType::findOne(['name'=>'Счетчик'])->id;
        return Device::find()->select(['id','name','descr'])->where(['=','type_id',$type])->orderBy('name', SORT_ASC)->asArray()->all();
    }

    //проверка корректности данных счетчика
    private function CheckCountVal($id,$val,$year,$month){
        $period = explode('-', date('Y-m', strtotime("$year-$month-01 -1 month"))); //определяем предыдущий период
        $y = $period[0];
        $m = $period[1];
        //выбираем данные за предыдущий период
        $numrow = CounterLog::find()->where(['device_id'=>$id,'_year'=>$y,'_month'=>$m])->count();
        if($numrow) {
            $row = CounterLog::find()->where(['device_id'=>$id,'_year'=>$y,'_month'=>$m])->limit(1)->asArray()->all();
            $this->previous = $row[0][encount];
            if($this->previous > $val)
                return self::MORE_VAL;
            else
                return self::LESS_VAL;
        }
        else return self::NOT_VAL;
    }

}
