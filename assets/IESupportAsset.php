<?php
namespace app\assets;

use yii\web\AssetBundle;
use yii\web\View;

class IESupportAsset extends AssetBundle
{
    public $css = [
        'css/ace-ie.min.css'
    ];
    public $js = [
        'https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js',
        'https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js'
    ];
    public $jsOptions = [
        'condition'=>'lt IE 9',
        'position' => View::POS_HEAD,
    ];
}