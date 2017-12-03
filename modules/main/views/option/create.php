<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\main\models\Option */

$this->title = 'Новый параметр';
$this->params['breadcrumbs'][] = ['label' => 'Параметры', 'url' => ['index', 'id' => $model->device_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="option-create">

    <h1 class="center"><?= Html::encode($this->title) ?> для <?= Html::encode($model->device->name) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
