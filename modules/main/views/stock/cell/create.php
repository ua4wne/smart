<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\main\models\Cell */

$this->title = 'Новое место хранения';
$this->params['breadcrumbs'][] = ['label' => 'Места хранения', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cell-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
