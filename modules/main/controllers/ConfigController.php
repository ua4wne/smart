<?php

namespace app\modules\main\controllers;

use app\modules\main\models\JsonAjax;
use app\modules\main\models\Mqtt;
use app\modules\main\models\MqttData;
use app\modules\main\models\Option;
use app\modules\main\models\Sms;
use app\modules\main\models\Topic;
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
            'pagination' => [
                'pageSize' => Yii::$app->params['page_size'],
            ],
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
                    Yii::$app->session->setFlash('success', 'Лимит бесплатных смс на сегодня исчерпан! Стоимость отправки - '.$cost.' руб.');
                    //$model->SendViaMail();
                }
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

    //работа с MQTT в конфигураторе
    public function actionMqtt(){
        $model = new Mqtt();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if($_POST['button']=='set-server');
                $this->SaveMqttConfig($model);
        }
        //определяем наличие констант для подключения к серверу MQTT
        $server = Config::findOne(['param'=>'MQTT_SERVER'])->val;
        $port = Config::findOne(['param'=>'MQTT_PORT'])->val;
        $login = Config::findOne(['param'=>'MQTT_LOGIN'])->val;
        $pass = Config::findOne(['param'=>'MQTT_PASSWORD'])->val;
        $model->server = $server;
        $model->port = $port;
        $model->login = $login;
        $model->pass = $pass;
        $topic = new Topic();
        $subquery = Topic::find()->select('option_id');
        $options = Option::find()->select(['id','device_id', 'name'])->where(['not in','id',$subquery])->all(); //выбираем только то, что еще не связано
        $selopt = array();
        foreach ($options as $option){
            $selopt[$option->id] = $option->device->name.' ('.$option->name.') - '.$option->device->location->name;
        }

        $subquery = Topic::find()->select('topic_id');
        $topics = MqttData::find()->select(['id', 'topic'])->where(['not in','id',$subquery])->all(); //выбираем только то, что еще не связано
        $seltop = array();
        foreach ($topics as $val){
            $seltop[$val->id] = $val->topic;
        }

        //выбираем сохраненные publish-топики
        $pubs = Topic::find()->select(['id','topic_id'])->where(['route'=>'public'])->all();
        $public = '';
        foreach ($pubs as $pub){
            $pub_top = MqttData::findOne($pub->topic_id)->topic;
            $public .= '<li class="pub" id="' . $pub->id . '"><pre>' . $pub_top . '<i class="fa fa-trash pubs pull-right" aria-hidden="true"></i></pre></li>';
        }

        //выбираем сохраненные subscribe-топики
        $subs = Topic::find()->select(['id','topic_id'])->where(['route'=>'subscribe'])->all();
        $subscribe = '';
        foreach ($subs as $sub){
            $sub_top = MqttData::findOne($sub->topic_id)->topic;
            $subscribe .= '<li class="sub" id="' . $sub->id . '"><pre>' . $sub_top . '<i class="fa fa-trash subs pull-right" aria-hidden="true"></i></pre></li>';
        }

        return $this->render('mqtt', [
            'model' => $model,
            'topic' => $topic,
            'selopt' => $selopt,
            'seltop' => $seltop,
            'public' => $public,
            'subscribe' => $subscribe,
        ]);
    }

    //обработчик сохранения топика в базу
    public function actionSaveTopic(){
        if(\Yii::$app->request->isAjax){
            $model = new Topic();
            if ($model->load(Yii::$app->request->post()) && $model->validate()){
                $dbl = Topic::findOne(['topic_id'=>$model->topic_id,'route'=>$model->route]);
                if(empty($dbl)){
                    if($model->save())
                        return $model->id;
                }
                else{
                    return 'DBL';
                }
            }
        }
    }

    //обработчик удаления топика из базы
    public function actionDelTopic(){
        if(\Yii::$app->request->isAjax){
            $id=$_POST['id'];
            if (($model = Topic::findOne($id)) !== null){
                $model->delete();
                return 'OK';
            }
        }
    }

    //обработчик сообщений топиков
    public function actionMqttmsg(){
        if(\Yii::$app->request->isAjax){
            $name = $_POST['topic'];
            $data = MqttData::findOne(['topic'=>$name]);
            $topic = Topic::findOne(['topic_id'=>$data->id]);
                if(!empty($topic)){
                    //есть такой топик, обновляем связанный параметр
                    $option = Option::findOne($topic->option_id);
                    if(!empty($option)){
                        $option->val = $_POST['payload'];
                        $option->save();
                    }
                    return 'OK';
                }
                else{
                    return 'NO';
                }
        }
    }

    //получение данных конфигурации MQTT
    public function actionMqttconf(){
        if(\Yii::$app->request->isAjax){
            //определяем наличие констант для подключения к серверу MQTT
            $server = Config::findOne(['param'=>'MQTT_SERVER'])->val;
            $port = Config::findOne(['param'=>'MQTT_PORT'])->val;
            $login = Config::findOne(['param'=>'MQTT_LOGIN'])->val;
            $pass = Config::findOne(['param'=>'MQTT_PASSWORD'])->val;
            $resp = ['server'=>$server, 'port'=>$port, 'login'=>$login, 'pass'=>$pass];
            return json_encode($resp,JSON_UNESCAPED_UNICODE);
        }
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

    protected function SaveMqttConfig(Mqtt $model){
        //определяем наличие записи
        $s = Config::findOne(['param'=>'MQTT_SERVER']);
        if(empty($s)){
            $s = new Config();
            $s->param = 'MQTT_SERVER';
        }
        $s->val = $model->server;
        $s->descr = 'Сервер MQTT Mosquitto';
        $s->save();
        //определяем наличие записи
        $p = Config::findOne(['param'=>'MQTT_PORT']);
        if(empty($p)){
            $p = new Config();
            $p->param = 'MQTT_PORT';
        }
        $p->val = $model->port;
        $p->descr = 'Порт сервера MQTT Mosquitto';
        $p->save();
        //определяем наличие записи
        $l = Config::findOne(['param'=>'MQTT_LOGIN']);
        if(empty($l)){
            $l = new Config();
            $l->param = 'MQTT_LOGIN';
        }
        $l->val = $model->login;
        $l->descr = 'Логин для сервера MQTT Mosquitto';
        $l->save();
        //определяем наличие записи
        $pw = Config::findOne(['param'=>'MQTT_PASSWORD']);
        if(empty($pw)){
            $pw = new Config();
            $pw->param = 'MQTT_PASSWORD';
        }
        $pw->val = $model->pass;
        $pw->descr = 'Пароль для сервера MQTT Mosquitto';
        $pw->save();
        Yii::$app->session->setFlash('success', 'Данные подключения к серверу MQTT сохранены');
    }
}
