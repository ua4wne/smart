<?php

namespace app\modules\main\controllers\stock;

use app\modules\main\models\Cell;
use app\modules\main\models\Unit;
use Yii;
use app\modules\main\models\Stock;
use app\modules\main\models\StockSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * StoreController implements the CRUD actions for Stock model.
 */
class StoreController extends Controller
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
     * Lists all Stock models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new StockSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Stock model.
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
     * Creates a new Stock model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Stock();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            $cells = array();
            $rows = Cell::find()->select(['id','name'])->asArray()->all();
            foreach ($rows as $row){
                $cells[$row[id]] = $row[name];
            }
            $units = array();
            $rows = Unit::find()->select(['id','name'])->asArray()->all();
            foreach ($rows as $row){
                $units[$row[id]] = $row[name];
            }
            return $this->render('create', [
                'model' => $model,
                'cells' => $cells,
                'units' => $units,
            ]);
        }
    }

    /**
     * Updates an existing Stock model.
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
            $cells = array();
            $rows = Cell::find()->select(['id','name'])->asArray()->all();
            foreach ($rows as $row){
                $cells[$row[id]] = $row[name];
            }
            $units = array();
            $rows = Unit::find()->select(['id','name'])->asArray()->all();
            foreach ($rows as $row){
                $units[$row[id]] = $row[name];
            }
            return $this->render('update', [
                'model' => $model,
                'cells' => $cells,
                'units' => $units,
            ]);
        }
    }

    /**
     * Deletes an existing Stock model.
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
     * Finds the Stock model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Stock the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Stock::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
