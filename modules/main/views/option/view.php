<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\main\models\Option */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Параметры', 'url' => ['index', 'id' => $model->device_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="option-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Обновить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить этот элемент?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'alias',
            'val',
            'min_val',
            'max_val',
            'unit',
            [
                'attribute'=>'device.name',
                'label'=>'Контроллер',
            ],
            'to_log',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
