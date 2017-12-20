<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\main\models\Option */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="option-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'option_id')->hiddenInput(['value'=>$val])->label(false) ?>

    <?= $form->field($model, 'condition')->dropDownList(['more'=>'Больше','less'=>'Меньше','equ'=>'Равно', 'not'=>'Не равно']) ?>

    <?= $form->field($model, 'val')->textInput() ?>

    <?= $form->field($model, 'action')->dropDownList(['sms'=>'Отправить СМС','mail'=>'Отправить e-mail','cmd'=>'Выполнить команду']) ?>

    <?= $form->field($model, 'text')->textarea(['rows' => 3, 'cols' => 5]) ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Сохранить' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
