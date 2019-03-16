<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\modules\main\models\Stock */

$this->title = $model->material->name;
$this->params['breadcrumbs'][] = ['label' => 'Остатки', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="stock-view">

    <h3 class="text-center"><?= Html::a($this->title,['stock/material/view', 'id' => $model->material_id],['target' => '_blank'])?></h3>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'label' => 'Изображение',
                'format' => 'raw',
                'value' => function($data){
                    return Html::img(Url::toRoute($data->image),[
                        'alt'=>'image',
                        'style' => 'width:200px;',
                        'class'=>'img-responsive center-block'
                    ]);
                },
            ],
            'cell.name',
            //'material.name',
            'quantity',
            'unit.name',
            'price',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
