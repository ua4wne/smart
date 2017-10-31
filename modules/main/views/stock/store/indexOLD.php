<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\modules\main\models\Stock;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\main\models\StockSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Остатки';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="stock-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Приход', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            //'id',
            //'cell_id',
            //'cell',
            [
                'attribute'=>'cell_id',
                'label'=>'Ячейка',
                'format'=>'text', // Возможные варианты: raw, html
                'content'=>function($data){
                    return $data->getCellName();
                },
                'filter' => Stock::getCells()
            ],
            [
                'attribute' => 'material_id',
                'format' => 'raw',
                'value' => function ($model, $key, $index, $column) {

                    return Html::a(Html::encode($model->material->name), ['view', 'id' => $model->id]);
                }
            ],
            'quantity',
            //'unit_id',
            [
                'attribute'=>'unit_id',
                'label'=>'Единица',
                'format'=>'text', // Возможные варианты: raw, html
                'content'=>function($data){
                    return $data->getUnitName();
                },
                'filter' => Stock::getUnits()
            ],
            'price',
            // 'created_at',
            // 'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
