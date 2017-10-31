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
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'cell.name',
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
