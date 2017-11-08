<?php
use sjaakp\gcharts\PieChart;
use sjaakp\gcharts\LineChart;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
$this->title = 'Счетчики';
$this->params['breadcrumbs'][] = $this->title;
$this->registerJsFile(
    'js/counter_modal.js',
    ['depends'=>'app\assets\AppAsset']
);
?>
<div class="registration-index">
    <h3 class="center">Данные за <?= date('Y'); ?> год</h3>
    <div class="row">
        <div class="col-md-3">
            <?= PieChart::widget([
                'height' => '400px',
                'dataProvider' => $dataProvider1,
                'columns' => [
                    'name:string',
                    'price'
                ],
                'options' => [
                    'title' => 'Затраты, руб'
                ],
            ]) ?>
        </div>
        <div class="col-md-9">
            <?= LineChart::widget([
                'height' => '400px',
                'dataProvider' => $dataProvider2,
                'columns' => [
                    'name:string',
                    'delta'
                ],
                'options' => [
                    'title' => 'Показания счетчиков'
                ],
            ]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?= $content; ?>
        </div>
    </div>
    <p>
        <?= Html::a('Новая запись', ['create'], ['class' => 'btn btn-success add-modal']) ?>
    </p>

    <!-- Modal "Добавить показания счетчика" -->
    <div class="modal fade" id="my-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-body">
                    ...
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

</div>
