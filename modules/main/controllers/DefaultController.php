<?php

namespace app\modules\main\controllers;

use app\models\Events;
use app\models\Weather;
use Yii;
use yii\web\Controller;
use yii\data\ActiveDataProvider;
use \yii\web\HttpException;
use app\modules\user\models\User;
use app\modules\main\models\Location;

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
        return $this->render('index',[
            'content' => $content,
            'tabs' => $tabs,
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
