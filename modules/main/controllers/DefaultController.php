<?php

namespace app\modules\main\controllers;

use app\models\Events;
use app\models\Weather;
use Yii;
use yii\web\Controller;
use yii\data\ActiveDataProvider;
use \yii\web\HttpException;
use app\modules\user\models\User;

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
        return $this->render('index',[
            'content' => $content,
        ]);
        /*else{
            throw new HttpException(404 ,'Доступ запрещен');
        }*/
    }

    public function actionForecast(){
        if(\Yii::$app->request->isAjax){
            $data =  Weather::GetForecast();
            return $data;
        }
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
            $user->fname = 'Администратор';
            $user->lname = 'системы';
            $user->setPassword('12345678');
            $user->status = 1;
            $user->role_id = 1;
            $user->generateAuthKey();
            if ($user->save()) {
                return 'Администратор системы создан. Данные для входа: admin (pass 12345678). После первого входа необходимо сменить пароль и установить реальный адрес e-mail!';
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

    protected function SysState(){
        //memory stat
        $stat['mem_percent'] = round(shell_exec("free | grep Mem | awk '{print $3/$2 * 100.0}'"),0);
        $mem_result = shell_exec("cat /proc/meminfo | grep MemTotal");
        $stat['mem_total'] = round(preg_replace("#[^0-9]+(?:\.[0-9]*)?#", "", $mem_result) / 1024 / 1024, 3);
        $mem_result = shell_exec("cat /proc/meminfo | grep MemFree");
        $stat['mem_free'] = round(preg_replace("#[^0-9]+(?:\.[0-9]*)?#", "", $mem_result) / 1024 / 1024, 3);
        $stat['mem_used'] = $stat['mem_total'] - $stat['mem_free'];
        //hdd stat
        $stat['hdd_free'] = round(disk_free_space("/") / 1024 / 1024 / 1024, 2);
        $stat['hdd_total'] = round(disk_total_space("/") / 1024 / 1024/ 1024, 2);
        $stat['hdd_used'] = $stat['hdd_total'] - $stat['hdd_free'];
        $stat['hdd_percent'] = round(sprintf('%.2f',($stat['hdd_used'] / $stat['hdd_total']) * 100), 0);

        $content=          '<div>
                                <p>
                                    <strong>Занято на диске</strong>
                                    <span class="pull-right text-muted">'.$stat['hdd_percent'].'%</span>
                                </p>
                                <div class="progress progress-striped active">';
        $sys_icon='fa fa-cogs fa-3x';
        if($stat['mem_percent']<50)
            $bar_state_mem='progress-bar progress-bar-info';
        else if($stat['mem_percent']>49&&$stat['hdd_percent']<75) {
            $bar_state_mem = 'progress-bar progress-bar-warning';
            $sys_icon='fa fa-warning fa-3x';
        }
        else {
            $bar_state_mem = 'progress-bar progress-bar-danger';
            $sys_icon='fa fa-warning fa-3x';
        }
        if($stat['hdd_percent']<55)
            $bar_state_hdd='progress-bar progress-bar-info';
        else if($stat['hdd_percent']>54&&$stat['hdd_percent']<85) {
            $bar_state_hdd = 'progress-bar progress-bar-warning';
            $sys_icon='fa fa-warning fa-3x';
        }
        else {
            $bar_state_hdd='progress-bar progress-bar-danger';
            $sys_icon='fa fa-warning fa-3x';
        }
        $content.='
                                    <div class="'.$bar_state_hdd.'" role="progressbar" aria-valuenow="'.$stat['hdd_percent'].'" aria-valuemin="0" aria-valuemax="100" style="width: '.$stat['hdd_percent'].'%">
                                        <span class="sr-only">'.$stat['hdd_percent'].'</span>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <p>
                                    <strong>Занято памяти</strong>
                                    <span class="pull-right text-muted">'.$stat['mem_percent'].'%</span>
                                </p>
                                <div class="progress progress-striped active">
                                    <div class="'.$bar_state_mem.'" role="progressbar" aria-valuenow="'.$stat['mem_percent'].'" aria-valuemin="0" aria-valuemax="100" style="width: '.$stat['mem_percent'].'%">
                                        <span class="sr-only">'.$stat['mem_percent'].'</span>
                                    </div>
                                </div>
                            </div>';

        return $content;
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
