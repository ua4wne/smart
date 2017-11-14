<?php
namespace app\components;

use app\modules\admin\models\Eventlog;
use yii\base\Widget;

class NumEvents extends Widget
{
    public function run(){
        $count = Eventlog::find()->count();
        return '<span class="badge">'.$count.'</span>';
    }
}