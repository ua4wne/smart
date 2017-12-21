<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datetime\DateTimePicker;

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

    <?= $form->field($model, 'runtime')->widget(DateTimePicker::className(),[
        'name' => 'runtime',
        'type' => DateTimePicker::TYPE_COMPONENT_PREPEND,
        'options' => ['placeholder' => 'Ввод даты/времени...'],
        'value'=> date("yyyy-MM-dd H:i:s"),
        'convertFormat' => true,
        'pluginOptions' => [
            'format' => 'yyyy-MM-dd H:i:s',
            'autoclose'=>true,
            'weekStart'=>1, //неделя начинается с понедельника
            'startDate' => '2015-01-01', //самая ранняя возможная дата
            'todayBtn'=>true, //снизу кнопка "сегодня"
        ]
    ]) ?>

    <?= $form->field($model, 'step')->textInput(['value' => 0]) ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Сохранить' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
