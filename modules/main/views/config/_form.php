<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\main\models\Config */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="config-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'param')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'val')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'descr')->textarea(['rows' => 6]) ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
