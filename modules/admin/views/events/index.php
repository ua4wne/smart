<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\main\models\MaterialSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'События';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="eventlog-index">

    <h1 class="center">События системы.</h1>

    <p>
        <?= Html::a('Очистить лог', ['empty-log'], ['class' => 'btn btn-danger', 'name' => 'clear_log', 'id' => 'clear_log']) ?>
    </p>
    <div class="events">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,

        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            [
                'attribute'=>'user.username',
                'label'=>'Логин',
            ],
            'user_ip',
            'type',
            'msg:html',
            'created_at',
            // 'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    </div>
</div>

<?php
$js = <<<JS
    $('#clear_log').click(function(e) {
		e.preventDefault();
		// отправляем AJAX запрос
		$.ajax({
			type: "POST",
		    url: "/admin/events/clear-log",
		    //dataType: "json",
	        data: {addvisitor:'set'},
		    // success - это обработчик удачного выполнения событий
		    success: function(res) {
			    //alert("Сервер вернул вот что: " + res);
			    $(".events").text(res);
			}     		 
     	});
	});
JS;

$this->registerJs($js);
?>