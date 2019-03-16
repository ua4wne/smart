<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\main\models\Stock */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="stock-form">

    <?php $form = ActiveForm::begin(['id'=>'f_pos']); ?>

    <?= $form->field($model, 'cell_id')->dropDownList($cells,['id'=>'cell_id']) ?>

    <?= $form->field($model, 'id')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'material_id')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'quantity')->textInput(['id'=>'quantity']) ?>

    <?= $form->field($model, 'unit_id')->dropDownList($units,['id'=>'unit_id']) ?>

    <?= $form->field($model, 'price')->textInput(['maxlength' => true,'id'=>'price']) ?>

    <div class="form-group">
        <?= Html::submitButton('Обновить', ['class' => 'btn btn-primary','id'=>'edit_pos']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
