<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
/* @var $this yii\web\View */
$this->title = 'Фильтр';
$this->params['breadcrumbs'][] = ['label' => 'Отчеты', 'url' => ['report/index']];
?>

<div class="report-filter">

    <h1 class="center"><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'start')->widget(DatePicker::className(),[
        'name' => 'start',
        'options' => ['placeholder' => 'Ввод даты'],
        'value'=> date("yyyy-MM-dd",strtotime($model->start)),
        'convertFormat' => true,
        'pluginOptions' => [
            'format' => 'yyyy-MM-dd',
            'autoclose'=>true,
            'weekStart'=>1, //неделя начинается с понедельника
            'startDate' => '2015-01-01', //самая ранняя возможная дата
            'todayBtn'=>true, //снизу кнопка "сегодня"
        ]
    ]) ?>

    <?= $form->field($model, 'finish')->widget(DatePicker::className(),[
        'name' => 'finish',
        'options' => ['placeholder' => 'Ввод даты'],
        'value'=> date("yyyy-MM-dd",strtotime($model->finish)),
        'convertFormat' => true,
        'pluginOptions' => [
            'format' => 'yyyy-MM-dd',
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

