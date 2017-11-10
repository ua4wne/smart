<?php

namespace app\modules\main\controllers;

use app\models\LibraryModel;
use app\modules\main\models\Config;
use app\modules\main\models\CounterLog;
use app\modules\main\models\Device;
use app\modules\main\models\DeviceType;
use app\modules\main\models\Tarif;
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
                        where _year='.$year.' group by name',
        ]);

        $dataProvider2 = new SqlDataProvider([
            'sql' =>  'select l._month as _month, sum(l.price) as price from counter_log l
                        inner join device d on d.id=l.device_id
                        where _year='.$year.' group by _month',
        ]);

        $content = CounterLog::StatCounter($year);

        return $this->render('index',[
            'dataProvider1' => $dataProvider1,
            'dataProvider2' => $dataProvider2,
            'content' => $content,
        ]);
    }

    public function actionSend(){
        $params = [];

        \Yii::$app->mailer->getView()->params['fio'] = Config::findOne(['param'=>'FULL_NAME'])->val;
        \Yii::$app->mailer->getView()->params['address'] = Config::findOne(['param'=>'POSTAL_ADDRESS'])->val;
        \Yii::$app->mailer->getView()->params['fls'] = Config::findOne(['param'=>'PERCONAL_ACCOUNT'])->val;

        $content = '';
        $type = DeviceType::findOne(['name'=>'Счетчик'])->id;
        $models = Device::find()->where(['=','type_id',$type])->orderBy('name', SORT_ASC)->all();

        //определяем предыдущий период
        $year = date('Y');
        $month = date('m');
        $period = explode('-', date('Y-m', strtotime("$year-$month-01 -1 month"))); //определяем предыдущий период
        $y = $period[0];
        $m = $period[1];
        foreach ($models as $model){
            $prev = CounterLog::find()->where(['device_id'=>$model->id,'_year'=>$y,'_month'=>$m])->limit(1)->all();
            $curr = CounterLog::find()->where(['device_id'=>$model->id,'_year'=>$year,'_month'=>$month])->limit(1)->all();
            if(empty($curr)){
                Yii::$app->session->setFlash('error', 'Не указано значение показаний счетчика'.' <strong>'.$model->name.'</strong> за текущий месяц!');
                \Yii::$app->mailer->getView()->params['fio'] = null;
                \Yii::$app->mailer->getView()->params['address'] = null;
                \Yii::$app->mailer->getView()->params['fls'] = null;
                \Yii::$app->mailer->getView()->params['table'] = null;

                return $this->redirect('/main/registration/index');
            }
            $content.='<tr><td>'.$model->name.'</td><td>'.$model->uid.'</td><td>'.$prev[0]->val.'</td><td>'.$curr[0]->val.'</td></tr>';
        }
        \Yii::$app->mailer->getView()->params['table'] = $content;
        $subject = 'Показания счётчиков за '.LibraryModel::SetMonth($month).' '.$year.' год.';
        //определяем получателей сообщения
        $respis = Config::findOne(['param'=>'TSJ_RECIPIENT'])->val;
        $resps = explode(',',$respis);
        foreach ($resps as $to){
            $result = \Yii::$app->mailer->compose([
                'html' => 'views/html_mail',
                //'text' => 'views/text_mail',
            ], $params)
                ->setFrom(Yii::$app->params['adminEmail'])
                ->setTo($to)
                ->setSubject($subject)
                ->send();
        }
        if($result)
            Yii::$app->session->setFlash('success', 'Сообщение отправлено!');
        else
            Yii::$app->session->setFlash('error', 'Ошибка! Сообщение не было отправлено.');

        // Reset layout params
        \Yii::$app->mailer->getView()->params['fio'] = null;
        \Yii::$app->mailer->getView()->params['address'] = null;
        \Yii::$app->mailer->getView()->params['fls'] = null;
        \Yii::$app->mailer->getView()->params['table'] = null;

        return $this->redirect('/main/registration/index');
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
            CounterLog::deleteAll(['device_id'=>$model->device_id,'_year'=>$model->_year,'_month'=>$model->_month]);
            if($result===self::LESS_VAL)
                $model->delta = $model->val - $this->previous;
            else
                $model->delta = $model->val; //первая запись или замена счетчика
            $koeff = Tarif::findOne(['device_id'=>$model->device_id])->koeff;
            $model->price = $model->delta * $koeff;
            $model->save();
            return 'OK';
        }

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
            $this->previous = $row[0][val];
            if($this->previous > $val)
                return self::MORE_VAL;
            else
                return self::LESS_VAL;
        }
        else return self::NOT_VAL;
    }

}
