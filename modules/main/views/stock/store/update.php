<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\main\models\Stock */

$this->title = 'Обновление: ' . $model->material->name;
$this->params['breadcrumbs'][] = ['label' => 'Остатки', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->material->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Обновление';
?>
<div class="stock-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'cells' => $cells,
        'units' => $units,
    ]) ?>

</div>
