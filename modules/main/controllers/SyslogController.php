<?php

namespace app\modules\main\controllers;

use app\modules\main\models\Syslog;
use yii\data\ActiveDataProvider;
use Yii;

class SyslogController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $query = Syslog::find();
        $dataProvider = new ActiveDataProvider([
            //'format' => 'raw',
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]],
            'pagination' => [
                'pageSize' => Yii::$app->params['page_size'],
            ],
        ]);
        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionDeleteAll()
    {
        Syslog::deleteAll();
        return $this->redirect(['index']);
    }

    /**
     * Deletes an existing Syslog model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    /**
     * Finds the Syslog model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Syslog the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Syslog::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
