<?php

namespace app\commands;
use yii\console\Controller;

class CronController extends Controller
{
    public function actionGetWeather()
    {
        echo "Get weather now!";
    }

}
