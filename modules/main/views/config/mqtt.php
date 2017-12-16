<?php

use yii\helpers\Html;
use yii\bootstrap\Modal;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->registerJsFile('/js/mqttws31.js',
    ['depends' => ['yii\web\JqueryAsset']]);
$this->registerJsFile('/js/mqtt.js',
    ['depends' => ['yii\web\JqueryAsset']]);

$this->title = 'MQTT';
$this->params['breadcrumbs'][] = ['label' => 'Параметры', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="option-index">

    <h1 class="center">Настройка работы по протоколу MQTT</h1>
    <div class="alert alert-danger" id="danger_msg">Error</div>
    <div class="alert alert-success" id="success_msg">Connected</div>


    <?php
        Modal::begin([
        'header' => '<h3>Настройка подключения к серверу MQTT</h3>',
        'toggleButton' => [
        'label' => 'Подключение',
        'tag' => 'button',
        'class' => 'btn btn-success',
        ],
        //'footer' => 'Mosquitto',
        ]); ?>

    <div class="config-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'server')->textInput(['maxlength' => true,'id'=>'mserver']) ?>

        <?= $form->field($model, 'port')->textInput(['maxlength' => true,'id'=>'mport']) ?>

        <?= $form->field($model, 'login')->textInput(['maxlength' => true,'id'=>'mlogin']) ?>

        <?= $form->field($model, 'pass')->passwordInput(['id'=>'mpass']) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success', 'id'=>'set-server', 'name'=>'button', 'value'=>'set-server']) ?>
    </div>

        <?php ActiveForm::end(); ?>

    </div>
     <?php   Modal::end();
    ?>

    <?php
    Modal::begin([
        'header' => '<h3>Публикация топика MQTT</h3>',
        'toggleButton' => [
            'label' => 'Публикация',
            'tag' => 'button',
            'class' => 'btn btn-primary',
        ],
        //'footer' => 'Mosquitto',
    ]); ?>
    <div class="topic-form">
        <?php $form = ActiveForm::begin(['action' =>['/main/config/save-topic'], 'id' => 'form-topic', 'method' => 'post']); ?>

        <?= $form->field($topic, 'name')->textInput(['maxlength' => true,'id'=>'name']) ?>

        <?= $form->field($topic, 'payload')->textInput(['maxlength' => true,'id'=>'payload']) ?>

        <?= $form->field($topic, 'route')->dropDownList(['public'=>'Публикация', 'subscribe'=>'Подписка'],['id'=>'route']) ?>

        <?= $form->field($topic, 'option_id')->dropDownList($selopt,['id'=>'option_id']) ?>

        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success', 'id'=>'set-topic']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
    <?php  Modal::end();
    ?>

    <div class="hr hr32 hr-dotted"></div>
    <div class="row">
        <div class="col-md-4">
            <div class="panel panel-info">
                <div class="panel-heading">
                    Поток данных с брокера Mosquitto
                </div>
                <div class="panel-body">
                    <ul id='ws' class="list-unstyled"><?php echo($accepted); ?></ul>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel panel-info">
                <div class="panel-heading">
                    Опубликованные топики
                </div>
                <div class="panel-body">
                    <ul id='publ' class="list-unstyled"><?php echo($public); ?></ul>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel panel-info">
                <div class="panel-heading">
                    Подписка на топики
                </div>
                <div class="panel-body">
                    <ul id='subs' class="list-unstyled"><?php echo($subscribe); ?></ul>
                </div>
            </div>
        </div>
    </div>

</div>
