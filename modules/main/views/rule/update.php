<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\main\models\Option */

$this->title = 'Обновление записи';
$this->params['breadcrumbs'][] = ['label' => 'Параметры', 'url' => ['index', 'id' => $model->option_id]];
$this->params['breadcrumbs'][] = ['label' => $model->option->name, 'url' => ['view', 'id' => $model->id]];
?>
<div class="option-update">

    <h1 class="center"><?= Html::encode($model->option->name) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
