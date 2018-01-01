<?php

namespace app\modules\main\controllers;

use app\models\Events;
use app\models\Weather;
use app\modules\main\models\Config;
use app\modules\main\models\Device;
use app\modules\main\models\Syslog;
use Yii;
use yii\web\Controller;
use yii\data\ActiveDataProvider;
use \yii\web\HttpException;
use app\modules\user\models\User;
use app\modules\main\models\Location;
use yii\data\SqlDataProvider;

/**
 * Default controller for the `main` module
 */
class DefaultController extends Controller
{
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
                'view' => '@app/modules/main/views/error/view.php',
            ],
        ];
    }

    public function actionIndex()
    {
        //читаем данные о погоде из файла
        $file = 'temp/forecast.xml';
        if(file_exists($file)){
            $data = simplexml_load_file($file);
            $content = Weather::GetContent($data);
        }
        $tabs = Location::GetTabs();
        $syslog = Syslog::ViewSysLog(10); //выводим последние 10 строк системного лога
        /*$dataProvider = new SqlDataProvider([
            'sql' =>  'select l.name as name, o.alias as alias, round(avg(lo.val),1) as val, substr(lo.created_at,1,10) as dat from logger lo
                        join options o on o.id = lo.option_id
                        join device d on d.id = o.device_id
                        join location l on l.id = d.location_id
                        where lo.created_at between \'2017-12-01\' and \'2017-12-31\'
                        group by alias, dat',
        ]);*/
        $device = new Device();
        $state = $device->GetState();
        return $this->render('index',[
            'content' => $content,
            'tabs' => $tabs,
            //'dataProvider' => $dataProvider,
            'syslog' => $syslog,
            'state' => $state,
        ]);
        /*else{
            throw new HttpException(404 ,'Доступ запрещен');
        }*/
    }

    public function actionChart(){
        if(\Yii::$app->request->isAjax){
            $location_id = Config::findOne(['param'=>'CHART_LOCATION_ID'])->val;
            if(!empty($location_id)){
                $finish = date('Y-m-d H:i:s');
                $timestamp = strtotime('-30 days',strtotime($finish));
                $start = date('Y-m-d H:i:s', $timestamp);
                $query = "select l.name as name, o.alias as alias, round(max(lo.val),1) as max, round(avg(lo.val),1) as val, substr(lo.created_at,1,10) as dat from logger lo
                        join options o on o.id = lo.option_id
                        join device d on d.id = o.device_id
                        join location l on l.id = d.location_id
                        where d.location_id = $location_id and lo.created_at between '$start' and '$finish'
                        group by alias, dat";
                // подключение к базе данных
                $connection = \Yii::$app->db;
                // Составляем SQL запрос
                $model = $connection->createCommand($query);
                //Осуществляем запрос к базе данных, переменная $model содержит ассоциативный массив с данными
                $rows = $model->queryAll();
                $celsio = array();
                $humi = array();
                foreach ($rows as $row){

                    if($row['alias']=='celsio'){
                        $celsio[$row['dat']] = $row['val'];
                    }
                    if($row['alias']=='humidity'){
                        $humi[$row['dat']] = $row['max'];
                    }

                }
                $dat = array();
                foreach ($celsio as $key=>$val){
                    $tmp = array();
                    $tmp['d'] = $key;
                    $tmp['t'] = $val;
                    $tmp['h'] = $humi[$key];
                    array_push($dat,$tmp);
                }
                return json_encode($dat);
            }
        }
    }

    public function actionForecast(){
        if(\Yii::$app->request->isAjax){
            $data =  Weather::GetForecast();
            return $data;
        }
    }

    public function actionUpdateVars(){
        $tabs = Location::GetTabs();
        return $tabs;
    }

    public function actionEvents(){
        if(Yii::$app->user->can('admin')) {
            $query = Events::find()->where(['=', 'is_read', 0]);
            $dataProvider = new ActiveDataProvider([
                //'format' => 'raw',
                'query' => $query,
                'sort' => ['defaultOrder' => ['id' => SORT_ASC]],
                'pagination' => [
                    'pageSize' => Yii::$app->params['page_size'],
                ],
            ]);
            $events = Events::find()->where(['=', 'is_read', 0])->count(); //общее число не прочитанных событий
            Yii::$app->session->setFlash('events', $events);
            return $this->render('events', [
                'dataProvider' => $dataProvider,
            ]);
        }
        else{
            throw new HttpException(404 ,'Доступ запрещен');
        }
    }

    public function actionView($id)
    {
        if(Yii::$app->user->can('admin')){
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        }
        else{
            throw new HttpException(404 ,'Доступ запрещен');
        }
    }

    public function actionUpdate($id)
    {
        if(Yii::$app->user->can('admin')) {
            $model = $this->findModel($id);
            $model->is_read = 1;
            $model->save();
            return $this->redirect('/main/default/events');
        }
        else{
            throw new HttpException(404 ,'Доступ запрещен');
        }
    }

    public function actionDelete($id)
    {
        if(Yii::$app->user->can('admin')) {
            $this->findModel($id)->delete();
            return $this->redirect(['/main/default/events']);
        }
        else{
            throw new HttpException(404 ,'Доступ запрещен');
        }
    }

    public function actionAddAdmin() {
    //    if(Yii::$app->user->can('admin')) {
            $model = User::find()->where(['username' => 'ircut'])->one();
        if (empty($model)) {
            $user = new User();
            $user->username = 'ircut';
            $user->email = 'admin@mail.com';
            $user->phone = '1234567890';
            $user->fname = 'Администратор';
            $user->lname = 'системы';
            $user->setPassword('12345678');
            $user->status = 1;
            $user->role_id = 1;
            $user->generateAuthKey();
            if ($user->save()) {
                return 'Администратор системы создан. Данные для входа: admin (pass 12345678). После первого входа необходимо сменить пароль и установить реальный адрес e-mail и телефон!';
            }
            else{
                throw new HttpException(500 ,'Ошибка выполнения');
            }
        }
    //    }
    //    else{
    //        throw new HttpException(404 ,'Действие запрещено');
    //    }
    }

    protected function findModel($id)
    {
        if (($model = Events::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
