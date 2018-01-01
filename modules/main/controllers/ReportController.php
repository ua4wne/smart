<?php

namespace app\modules\main\controllers;

use app\modules\main\models\Logger;
use app\modules\main\models\Option;
use Yii;
use app\modules\main\models\ReportFilter;
use yii\data\ActiveDataProvider;

class ReportController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $model = new ReportFilter();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $dataProvider = new ActiveDataProvider([
                'query' => Logger::find()->where(['option_id'=>$model->option_id])->andWhere(['between', 'created_at', $model->start, $model->finish]),
                'pagination' => false
            ]);

            return $this->render('view',[
                'option' => Option::findOne($model->option_id)->name,
                'unit' => Option::findOne($model->option_id)->unit,
                'dataProvider' => $dataProvider,
                'start' => $model->start,
                'finish' => $model->finish,
            ]);
        }
        else{
            $rows = Logger::find()->select('option_id')->distinct()->all();
            $option = array();
            foreach ($rows as $row){
                $location = $row->option->device->location->name;
                $option[$row->option_id] = $row->option->name . " ($location)";
            }
            return $this->render('index', [
                'model' => $model,
                'option' => $option,
            ]);
        }
    }

}
