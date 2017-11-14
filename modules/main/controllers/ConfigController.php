<?php

namespace app\modules\main\controllers;

use app\modules\main\models\Sms;
use app\modules\user\models\User;
use Yii;
use app\modules\main\models\Config;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ConfigController implements the CRUD actions for Config model.
 */
class ConfigController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Config models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Config::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Config model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Config model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Config();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Config model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Config model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        Yii::$app->session->setFlash('error', 'Удаление системной константы запрещено!');
        //$this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    //тест отправки смс
    public function actionSms(){
        $model = new Sms();
        $uid= Yii::$app->user->identity->getId();
        $model->phone = User::findOne($uid)->phone;
        if ($model->load(Yii::$app->request->post())) {
            $model->phone = '7' . str_replace('-','',$model->phone);
            if($model->from_mail){
                //$to = Yii::$app->params['mail_sms'];
                //return 'Отправка будет через ящик '.$to;
                $model->SendViaMail();
                
            }
            else{
                $cost = $model->GetCost();
                if($cost>0){
                    Yii::$app->session->setFlash('success', 'Лимит бесплатных смс на сегодня исчерпан! Смс будет отправлено через почту.');
                    $model->SendViaMail();
                }
                else
                    $model->SendSms();
            }
            //сбросили значения
            $model->phone = User::findOne($uid)->phone;
            $model->message = '';
        }

        return $this->render('sms', [
            'model' => $model,
        ]);
    }

    /**
     * Finds the Config model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Config the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Config::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
