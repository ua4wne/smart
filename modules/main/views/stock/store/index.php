<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\main\models\StockSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Остатки';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="stock-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Приход', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= $content ?>

</div>

<?php
$js = <<<JS
$(document).ready(function(){
    $('#dataTables-stock').DataTable({
        responsive: true
    });
});

JS;
$this->registerJs($js);
?>
