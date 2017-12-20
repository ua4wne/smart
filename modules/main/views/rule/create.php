<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\main\models\Option */

$this->title = 'Новое правило';
$this->params['breadcrumbs'][] = ['label' => $model->option->name, 'url' => ['index', 'id' => $model->option_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rule-create">

    <h1 class="center"><?= Html::encode($this->title) ?> для параметра <em><?= Html::encode($model->option->name) ?></em></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
