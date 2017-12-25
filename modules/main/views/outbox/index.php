<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Системные сообщения';
$this->params['breadcrumbs'][] = ['label' => 'Сообщения', 'url' => ['outbox/index']];

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
            'from',
            'to',
            'msg',
            'is_new',
            /*[
                'attribute' => 'is_new',
                'format' => 'raw',
                'content'=>function($data){
                    switch ($data->is_new) {
                        case 0:
                            return '<i class="ace-icon fa fa-envelope-o green"></i>';
                            break;
                        case 1:
                            return '<i class="ace-icon fa fa-envelope orange"></i>';
                            break;
                    }
                },
                'filter'=>array("0"=>"Не прочтено","1"=>"Прочтено"),
            ],*/
            'created_at',
            //'updated_at',

            ['class' => 'yii\grid\ActionColumn',
                'header'=>'Действия',
                'headerOptions' => ['width' => '50'],
                'template' => '{view}  {delete}',
            ],
        ],
    ]); ?>
</div>

<?php
$js = <<<JS

    $('.btn-danger').on('click', function(){
        if (confirm('Все прочтенные сообщения будут удалены. Продолжить?'))
            return true;
        else
            return false;
    });

JS;

$this->registerJs($js);
?>
