<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\main\models\MaterialSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $type;
$this->params['breadcrumbs'][] = ['label' => 'События', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="eventlog-index">

        <h1 class="center">События системы.</h1>

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
                    //'type',
                    [
                        'attribute' => 'type',
                        'format' => 'raw',
                        'value' => function ($model, $key, $index, $column) {
                            /** @var User $model */
                            return Html::a(Html::encode($model->type), ['view', 'type' => $model->type]);
                        }
                    ],
                    'msg:html',
                    'created_at',
                    // 'updated_at',

                    ['class' => 'yii\grid\ActionColumn'],
                ],
            ]); ?>
        </div>
    </div>