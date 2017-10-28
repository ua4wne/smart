<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\main\models\Material */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Номенклатура', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Обновление записи';
?>
<div class="material-update">

    <h1><?= Html::encode($this->title) ?></h1>
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <?= Html::img(Url::toRoute($model->image),[
                'alt'=>'image',
                'style' => 'width:300px;',
                //'class'=>'img-circle'
            ]); ?>
        </div>
    </div>
    <div class="material-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'category_id')->dropDownList($catsel) ?>

        <?= $form->field($upload, 'new_image')->fileInput() ?>

        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
