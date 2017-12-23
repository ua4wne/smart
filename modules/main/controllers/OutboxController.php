<?php

namespace app\modules\main\controllers;

class OutboxController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

}
