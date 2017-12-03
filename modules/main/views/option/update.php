<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\main\models\Option */

$this->title = 'Обновление записи';
$this->params['breadcrumbs'][] = ['label' => 'Параметры', 'url' => ['index', 'id' => $model->device_id]];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
?>
<div class="option-update">

    <h1 class="center"><?= Html::encode($model->name) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
