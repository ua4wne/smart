<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\main\models\Option */

$this->title = $model->option->name;
$this->params['breadcrumbs'][] = ['label' => 'Правила', 'url' => ['index', 'id' => $model->option_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="option-view">

    <h1><?= Html::encode('Правило №'.$model->id) ?></h1>

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
            [
                'attribute'=>'option.name',
                'label'=>'Параметр',
            ],
            'condition',
            'val',
            'action',
            'text',
            'runtime',
            'step',
            //'expire',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
