<?php

namespace app\modules\main\controllers\stock;

use app\modules\main\models\Cell;
use app\modules\main\models\Unit;
use Yii;
use app\modules\main\models\Stock;
use app\modules\main\models\StockSearch;
use app\modules\main\models\MyStockSearch;
use yii\data\SqlDataProvider;
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
        //$searchModel = new StockSearch();
        //$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        /*$dataProvider = new SqlDataProvider([
            'sql' =>  'SELECT c.name AS cell, m.name AS material, m.image AS images, cat.name AS category, s.quantity AS quantity, u.name AS unit, s.price AS price ' .
                'FROM Stock s ' .
                'INNER JOIN cell c ON (c.id = s.cell_id) ' .
                'INNER JOIN material m ON (m.id = s.material_id) '.
                'INNER JOIN category cat ON (cat.id = m.category_id) '.
                'INNER JOIN unit u ON (u.id = s.unit_id) ',
        ]); */
        $content = Stock::ViewStock();
        return $this->render('index',[
            'content' => $content,
        ]);
        /*return $this->render('indexOLD', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'pagination' => [
                'pagesize' => 10,
            ],
        ]);*/
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
        if (($model = Stock::findOne(['material_id'=>$id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
