<?php

namespace app\modules\main\controllers\stock;

use app\modules\main\models\Cell;
use app\modules\main\models\Unit;
use Yii;
use app\modules\main\models\Stock;
use app\modules\main\models\StockSearch;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use kartik\mpdf\Pdf;

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
        return $this->render('index', [
            'content' => $content,
        ]);
        /*return $this->render('indexOLD', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);*/
    }

    /**
     * Displays a single Stock model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->renderPartial('view', [
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
            $rows = Cell::find()->select(['id', 'name'])->asArray()->all();
            foreach ($rows as $row) {
                $cells[$row[id]] = $row[name];
            }
            $units = array();
            $rows = Unit::find()->select(['id', 'name'])->asArray()->all();
            foreach ($rows as $row) {
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
    public function actionViewUpdate($id)
    {
        $model = $this->findModel($id);
        if (\Yii::$app->request->isAjax) {
            $cells = array();
            $rows = Cell::find()->select(['id', 'name'])->asArray()->all();
            foreach ($rows as $row) {
                $cells[$row[id]] = $row[name];
            }
            $units = array();
            $rows = Unit::find()->select(['id', 'name'])->asArray()->all();
            foreach ($rows as $row) {
                $units[$row[id]] = $row[name];
            }
            return $this->renderPartial('update', [
                'model' => $model,
                'cells' => $cells,
                'units' => $units,
            ]);
        }
    }

    public function actionUpdatePos()
    {
        $new = new Stock();
        if (\Yii::$app->request->isAjax) {
            if ($new->load(Yii::$app->request->post())) {
                $model = $this->findModel($new->id);
                if(!empty($model)) {
                    $model->cell_id = $new->cell_id;
                    $model->material_id = $new->material_id;
                    $model->quantity = $new->quantity;
                    $model->unit_id = $new->unit_id;
                    $model->price = $new->price;
                    if($model->save())
                        return 'OK';
                    else
                        return 'ERR';
                }
            }
        }
    }

    /**
     * Deletes an existing Stock model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete()
    {
        if (\Yii::$app->request->isAjax) {
            $id = $_POST['id'];
            if ($this->findModel($id)->delete())
                return 'OK';
            else
                return 'ERR';
        }

        //return $this->redirect(['index']);
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

    public function actionInventory()
    {
        $searchModel = new StockSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('inventory', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionExport()
    {
        $query = Stock::find()->orderBy(['cell_id' => SORT_ASC]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $dataProvider->pagination = false;
        // get your HTML raw content without any layouts or scripts
        $content = $this->renderPartial('to_pdf', [
            'dataProvider' => $dataProvider,
            //'sort'=> ['defaultOrder' => ['cell_id'=>SORT_ASC]]
        ]);

        // setup kartik\mpdf\Pdf component
        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_UTF8, //чтобы русские буквы отображались
            // A4 paper format
            'format' => Pdf::FORMAT_A4,
            // portrait orientation
            'orientation' => Pdf::ORIENT_PORTRAIT,
            // stream to browser inline
            'destination' => Pdf::DEST_BROWSER,
            // your html content input
            'content' => $content,
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
            // any css to be embedded if required
            'cssInline' => '.kv-heading-1{font-size:10px}',
            // set mPDF properties on the fly
            'options' => ['title' => 'Инвентаризация'],
            // call mPDF methods on the fly
            'methods' => [
                'SetHeader' => ['Инвентаризация'],
                'SetFooter' => ['{PAGENO}'],
            ]
        ]);

        // return the pdf output as per the destination setting
        return $pdf->render();
    }
}
