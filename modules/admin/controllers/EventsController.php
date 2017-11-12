<?php

namespace app\modules\admin\controllers;

use yii\web\Controller;
use app\modules\admin\models\Eventlog;
use yii\data\ActiveDataProvider;
use Yii;

class EventsController extends Controller
{
    public $layout = '@app/views/layouts/main.php';

    public function actionIndex()
    {
        $query = Eventlog::find()->where(['is_read'=>0]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['create_at' => SORT_DESC]],
            'pagination' => [
                'pageSize' => Yii::$app->params['page_size'],
            ],
        ]);
        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionClearLog(){
        //if(Yii::$app->user->can('admin')) {
            if (\Yii::$app->request->isAjax) {
                $query=Yii::$app->db->createCommand("delete from eventlog where type != 'error' and is_read = 0");
                $logs = $query->execute();
                return 'Журнал событий очищен';
            }
        //}
        //else{
       //     throw new HttpException(404 ,'Доступ запрещен');
        //}
    }

}
