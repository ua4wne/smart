<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datetime\DateTimePicker;
/* @var $this yii\web\View */
$this->title = 'Фильтр';
$this->params['breadcrumbs'][] = ['label' => 'Отчеты', 'url' => ['report/index']];
?>

<div class="report-filter">

    <h1 class="center"><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'start')->widget(DateTimePicker::className(),[
        'name' => 'start',
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

    <?= $form->field($model, 'finish')->widget(DateTimePicker::className(),[
        'name' => 'finish',
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

    <?= $form->field($model, 'option_id')->dropDownList($option) ?>


    <div class="form-group">
        <?= Html::submitButton('Отчет', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<div id="chart"></div>

<?php
$js = <<<JS


/*$.ajax({
     url: '/main/default/chart',
     type: 'POST',
     data: {'type':'line'},
         success: function(res){
         //alert("Сервер вернул вот что: " + res);
         $("#chart-main").empty();
             Morris.Line({
                  element: 'chart-main',
                  data: JSON.parse(res),
                  xkey: 'd',
                  ykeys: ['t','h'],
                  labels: ['Температура, С','Влажность, %']
              });
        }
    });*/

JS;
?>

