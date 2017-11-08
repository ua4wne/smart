<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\models\gbUser $model
 * @var yii\widgets\ActiveForm $form
 */
?>
<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Закрыть</span></button>
<div id="success"> </div> <!-- For success message -->

<div class="gb-user-form">

    <?php $form = ActiveForm::begin(['options' => ['class' => 'modal-form']]); ?>

    <?= $form->field($model, '_year')->textInput(['value' => $year, 'required'=>true], ['class' => 'input-modal']) ?>

    <?= $form->field($model, '_month')->dropDownList($month,['options' =>[ $smonth => ['Selected' => true]]]) ?>

    <?= $form->field($model, 'device_id')->dropDownList($selcnt) ?>

    <?= $form->field($model, 'val')->textInput(['maxlength' => 255, 'required'=>true], ['class' => 'input-modal']) ?>

    <div class="form-group text-right">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success btn-update-password']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>