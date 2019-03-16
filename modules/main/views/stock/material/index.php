<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\main\models\MaterialSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Номенклатура';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="material-index">

    <h1 class="center"><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Новая запись', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            [
                'label' => 'Фото',
                'format' => 'raw',
                'value' => function($data){
                    return Html::img(Url::toRoute($data->image),[
                        'alt'=>'image',
                        'style' => 'width:50px;',
                        'class'=>'img-circle'
                    ]);
                },
            ],
            'name',
            //'category_id',
            [
                'attribute'=>'category_id',
                'label'=>'Категория',
                'format'=>'text', // Возможные варианты: raw, html
                'content'=>function($data){
                    return $data->getCategoryName();
                },
                'filter' => \app\modules\main\models\Material::getCategories()
            ],
            //'image',
            //'created_at',
            // 'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
