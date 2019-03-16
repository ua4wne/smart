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

    <h1 class="center"><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Приход', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= $content ?>

    <!-- Modal "Карточка товара" -->
    <div class="modal fade" id="my-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4 class="modal-title" id="ModalLabel"></h4>
                </div>
                <div class="modal-body">
                    ...
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

</div>

<?php
$js = <<<JS
$(document).ready(function(){
    $('#dataTables-stock').DataTable({
        responsive: true
    });
    
    $(document).on ({
        click: function(e) { // нажатие на кнопку - выпадает модальное окно
            e.preventDefault();
            var modalContainer = $('#my-modal');
            //var modalBody = modalContainer.find('.modal-body');
            modalContainer.modal({show:true});
            var id = $(this).parent().parent().attr('id');
            var pname = $(this).parent().prevAll().eq(4).text();
            $.ajax({
                url: '/main/stock/store/view-update',
                type: "GET",
                data: {'id':id},
                success: function (res) {
                    //alert("Сервер вернул вот что: " + res);
                    $('.modal-body').html(res);
                    $('#ModalLabel').text('Редактирование '+pname);
                    //modalContainer.modal({show:true});
                },
                error: function(){
                    alert('Error!');
                }
            });
        }
    }, ".doc_edit");
    
    $(document).on ({
        click: function(e) {
            e.preventDefault();
            var modalContainer = $('#my-modal');
            var data = $('#f_pos').serialize();
            var id = $('#stock-id').val();
            var cell = $('#cell_id option:selected').text();
            var unit = $('#unit_id option:selected').text();
            var qnt = $('#quantity').val();
            var price = $('#price').val();
            $.ajax({
                url: '/main/stock/store/update-pos',
                type: "POST",
                data: data,
                success: function (res) {
                    //alert("Сервер вернул вот что: " + res);
                    if(res=='OK'){
                        $('#'+id).children('td').eq(1).text(cell);
                        $('#'+id).children('td').eq(4).text(qnt);
                        $('#'+id).children('td').eq(5).text(unit);
                        $('#'+id).children('td').eq(6).text(price);
                        modalContainer.hide();
                        $('.close').click();
                    }
                },
                error: function(){
                    alert('Error!');
                }
            });
        }
    }, "#edit_pos" );
    
    $(document).on ({
        click: function() {
            var modalContainer = $('#my-modal');
            //$('.modal-body').clear();
            //var modalBody = modalContainer.find('.modal-body');
            modalContainer.modal({show:true});
            var id = $(this).parent().parent().attr('id');
            $.ajax({
                url: '/main/stock/store/view',
                type: "GET",
                data: {'id':id},
                success: function (res) {
                    //alert("Сервер вернул вот что: " + res);
                    $('.modal-body').html(res);
                    $('#ModalLabel').text('Просмотр позиции');
                    modalContainer.modal({show:true});
                },
                error: function(){
                    alert('Error!');
                }
            });
        }
    }, ".doc_view" );
    
    $(document).on ({
            click: function() {
                var id = $(this).parent().parent().attr('id');
                var x = confirm("Выбранная запись будет удалена. Продолжить (Да/Нет)?");
                if (x) {
                    $.ajax({
                        url: '/main/stock/store/delete',
                        type: "POST",
                        data: {'id':id},
                        success: function (res) {
                            //alert("Сервер вернул вот что: " + res);
                            if(res=='OK')
                                $('#'+id).hide();
                        },
                        error: function(){
                            alert('Error!');
                        }
                    });
                }
                else {
                    return false;
                }
            }
        }, ".doc_delete" );
});

JS;
$this->registerJs($js);
?>
