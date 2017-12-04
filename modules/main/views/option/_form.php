<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\main\models\Option */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="option-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'device_id')->hiddenInput(['value'=>$val])->label(false) ?>

    <?= $form->field($model, 'val')->textInput(['value' => 0]) ?>

    <?= $form->field($model, 'unit')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'alias')->dropDownList(['state'=>'Состояние','celsio'=>'Температура','humidity'=>'Влажность', 'pressure'=>'Давление', 'light'=>'Освещенность', 'alarm'=>'Контроль']) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'to_log')->dropDownList(['1'=>'Да','0'=>'Нет']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Сохранить' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
