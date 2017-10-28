<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\main\models\Stock */

$this->title = 'Приход номенклатуры';
$this->params['breadcrumbs'][] = ['label' => 'Остатки', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="stock-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'cells' => $cells,
        'units' => $units,
    ]) ?>

</div>
