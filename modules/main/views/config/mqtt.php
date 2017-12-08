<?php

use yii\helpers\Html;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'MQTT';
$this->params['breadcrumbs'][] = ['label' => 'Параметры', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="option-index">

    <h1 class="center">Настройка работы по протоколу MQTT</h1>
    <div class="alert alert-danger">Error</div>
    <div class="alert alert-success">Connected</div>
    <p>

    <?php
        Modal::begin([
        'header' => '<h3>Настройка подключения к серверу MQTT</h3>',
        'toggleButton' => [
        'label' => 'Подключение',
        'tag' => 'button',
        'class' => 'btn btn-success',
        ],
        //'footer' => 'Mosquitto',
        ]);

        echo 'Say hello...';
        Modal::end();
    ?>
        <?= Html::Button('Subscribe', ['class' => 'btn btn-primary', 'id'=>'subscribe']) ?>
        <?= Html::Button('Unsubscribe', ['class' => 'btn btn-inverse', 'id'=>'unsubscribe']) ?>
    </p>
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
