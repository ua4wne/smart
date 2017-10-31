<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\main\models\StockSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Инвентаризация';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="stock-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('<span class="fa  fa-file-excel-o"></span> Скачать', ['export'], ['class' => 'btn btn-primary']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            [
                'attribute'=>'cell_id',
                'label'=>'Ячейка',
                'format'=>'text', // Возможные варианты: raw, html
                'content'=>function($data){
                    return $data->getCellName();
                },
                'filter' => \app\modules\main\models\Stock::getCells()
            ],
            'material.name',
            'quantity',
            'unit.name',
            'price',
            //'created_at',
            // 'updated_at',

            //['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>

<?php
$js = <<<JS
$(document).ready(function(){
    $('#dataTables-stock').DataTable({
        responsive: true
    });
});

JS;
$this->registerJs($js);
?>
