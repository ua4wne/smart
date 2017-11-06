<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\main\models\Location */

$this->title = 'Новая запись';
$this->params['breadcrumbs'][] = ['label' => 'Локации', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="location-create">

    <h1 class="center"><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
