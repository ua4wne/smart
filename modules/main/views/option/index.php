<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Оборудование', 'url' => ['device/index']];
$this->params['breadcrumbs'][] = 'Параметры';
?>
<div class="option-index">

    <h1 class="center"><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Новый параметр', ['create', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            //'device_id',
            //'name',
            [
                'attribute' => 'name',
                'format' => 'raw',
                'value' => function ($model, $key, $index, $column) {
                    /** @var User $model */
                    if($model->getRuleCount())
                        return Html::a(Html::encode($model->name), ['/main/rule/index', 'id' => $model->id]).' <span class="badge">'.$model->getRuleCount().'</span>';
                    else
                        return Html::a(Html::encode($model->name), ['/main/rule/index', 'id' => $model->id]);
                }
            ],
            'alias',
            'val',
            'min_val',
            'max_val',
            'unit',
            'to_log',
            //'created_at',
            'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
