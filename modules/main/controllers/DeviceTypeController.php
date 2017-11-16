<?php

namespace app\modules\main\controllers;

use Yii;
use app\modules\main\models\DeviceType;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\main\models\Device;

/**
 * DeviceTypeController implements the CRUD actions for DeviceType model.
 */
class DeviceTypeController extends Controller
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
     * Lists all DeviceType models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => DeviceType::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'pagination' => [
                'pageSize' => Yii::$app->params['page_size'],
            ],
        ]);
    }

    /**
     * Displays a single DeviceType model.
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
     * Creates a new DeviceType model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new DeviceType();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing DeviceType model.
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
     * Deletes an existing DeviceType model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        //ищем связанные данные
        $device = Device::find()->where(['=','type_id',$id])->count();
        if($device){
            Yii::$app->session->setFlash('error', 'Имеются связанные устройства ('.$device.'). Удаление не возможно!');
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        }
        else{
            $type = $this->findModel($id);
            Yii::$app->session->setFlash('success', 'Тип устройств <strong>'.$type->name.'</strong> удален!');
            $this->findModel($id)->delete();
        }
        return $this->redirect(['index']);
    }

    /**
     * Finds the DeviceType model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DeviceType the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DeviceType::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
