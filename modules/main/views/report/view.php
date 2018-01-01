<?php
use sjaakp\gcharts\LineChart;
/* @var $this yii\web\View */
$this->title = $option;
$this->params['breadcrumbs'][] = ['label' => 'Отчеты', 'url' => ['report/index']];
?>

<div class="row">
    <div class="view-report col-md-12">
        <?= LineChart::widget([
            'height' => '500px',
            'dataProvider' => $dataProvider,
            'columns' => [
                'created_at:string',
                "val:number:$option, $unit",
            ],
            'options' => [
                'title' => 'Данные за период с '.$start.' по '.$finish,
                'sliceVisibilityThreshold' => 0.022,
            ],
        ]) ?>
    </div>
</div>
