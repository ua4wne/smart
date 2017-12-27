<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Системный лог';
$this->params['breadcrumbs'][] = ['label' => 'Оборудование', 'url' => ['device/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="option-index">

    <h1 class="center"><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Очистить', ['delete-all'], ['class' => 'btn btn-danger']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            //'device_id',
            //'type',
            [
                'attribute' => 'type',
                'format' => 'raw',
                'content'=>function($data){
                    switch ($data->type) {
                        case 'error':
                            return '<i class="ace-icon fa fa-bug red"></i>';
                            break;
                        case 'exec':
                            return '<i class="ace-icon fa fa-terminal"></i>';
                            break;
                        case 'sms':
                            return '<i class="ace-icon fa fa-volume-control-phone green"></i>';
                            break;
                        case 'email':
                            return '<i class="ace-icon fa fa-envelope orange"></i>';
                            break;
                    }
                },
                'filter'=>array("error"=>"Ошибки","info"=>"Информация","sms"=>"Отправка СМС","email"=>"E-Mail"),
            ],
            'from',
            'to',
            'msg:html',
            [
                'attribute' => 'is_new',
                'format' => 'raw',
                'label' => 'Статус',
                'content'=>function($data){
                    switch ($data->is_new) {
                        case 0:
                            return '<i class="ace-icon fa fa-envelope-open green"></i>';
                            break;
                        case 1:
                            return '<i class="ace-icon fa fa-envelope orange"></i>';
                            break;
                    }
                },
                'filter'=>array("1"=>"Не прочтено","0"=>"Прочтено"),
            ],
            'created_at',
            //'updated_at',

            ['class' => 'yii\grid\ActionColumn',
                'header'=>'Действия',
                'headerOptions' => ['width' => '80'],
                'template' => '{view}  {delete}',
            ],
        ],
    ]); ?>
</div>

<?php
$js = <<<JS

    $('.btn-danger').on('click', function(){
        if (confirm('Все данные журнала будут удалены. Продолжить?'))
            return true;
        else
            return false;
    });

JS;

$this->registerJs($js);
?>

