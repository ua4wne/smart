<?php

namespace app\modules\main\controllers\stock;

use app\modules\main\models\Category;
use app\modules\main\models\Stock;
use Yii;
use app\modules\main\models\Material;
use app\modules\main\models\MaterialSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\main\models\UploadImage;
use yii\web\UploadedFile;

/**
 * MaterialController implements the CRUD actions for Material model.
 */
class MaterialController extends Controller
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
     * Lists all Material models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MaterialSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Material model.
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
     * Creates a new Material model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Material();
        $upload = new UploadImage();

        if ($model->load(Yii::$app->request->post())) {
            $upload->image = UploadedFile::getInstance($upload, 'image');
            $fname = $upload->upload();
            //обновляем данные картинки
            $model->image = $fname;
            if($model->save())
                return $this->redirect(['view', 'id' => $model->id]);
        }
        else {
            $categories = Category::find()->select(['id','name'])->asArray()->all();
            $catsel = array();
            foreach($categories as $category) {
                $catsel[$category['id']] = $category['name']; //массив для заполнения данных в select формы
            }
            return $this->render('create', [
                'model' => $model,
                'upload' => $upload,
                'catsel' => $catsel,
            ]);
        }
    }

    /**
     * Updates an existing Material model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $old_image = substr($model->image,1); //старый файл изображения
        $upload = new UploadImage();

        if ($model->load(Yii::$app->request->post()) ) {
            $upload->image = UploadedFile::getInstance($upload, 'new_image');
            if(!empty($upload->image)){
                $fname = $upload->upload();
                //обновляем данные картинки
                $model->image = $fname;
                //удаляем связанный файл изображения если это не общая картинка noimage.jpg
                $pos = strpos($old_image, 'noimage.jpg');
                if($pos === false)
                    unlink($old_image);
            }
            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            $categories = Category::find()->select(['id','name'])->asArray()->all();
            $catsel = array();
            foreach($categories as $category) {
                $catsel[$category['id']] = $category['name']; //массив для заполнения данных в select формы
            }
            return $this->render('update', [
                'model' => $model,
                'upload' => $upload,
                'catsel' => $catsel,
            ]);
        }
    }

    /**
     * Deletes an existing Material model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $rows = Stock::find()->where(['material_id'=>$id])->sum('quantity');
        //return print_r($rows);
        if($rows){
            Yii::$app->session->setFlash('error', 'Номенклатура <strong>'. $this->findModel($id)->name .'</strong> не может быть удалена, т.к. имеются её остатки ('.$rows.') на складе!');
        }
        else {
            $fname = substr($this->findModel($id)->image,1);
            $this->findModel($id)->delete();
            //удаляем связанный файл изображения если это не общая картинка noimage.jpg
            $pos = strpos($fname, 'noimage.jpg');
            if($pos === false)
                unlink($fname);
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the Material model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Material the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Material::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
