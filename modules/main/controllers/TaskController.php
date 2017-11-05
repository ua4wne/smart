<?php

namespace app\modules\main\controllers;

class TaskController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

}
