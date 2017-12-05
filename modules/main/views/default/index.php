<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->registerJsFile('/js/justgage.js',
    ['depends' => ['yii\web\JqueryAsset']]);

$this->title = 'Панель управления';
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="ace-settings-container" id="ace-settings-container">
        <div class="btn btn-app btn-xs btn-warning ace-settings-btn" id="ace-settings-btn">
            <i class="ace-icon fa fa-cog bigger-130"></i>
        </div>

        <div class="ace-settings-box clearfix" id="ace-settings-box">
            <div class="pull-left width-50">
                <div class="ace-settings-item">
                    <div class="pull-left">
                        <select id="skin-colorpicker" class="hide">
                            <option data-skin="no-skin" value="#438EB9">#438EB9</option>
                            <option data-skin="skin-1" value="#222A2D">#222A2D</option>
                            <option data-skin="skin-2" value="#C6487E">#C6487E</option>
                            <option data-skin="skin-3" value="#D0D0D0">#D0D0D0</option>
                        </select>
                    </div>
                    <span>&nbsp; Choose Skin</span>
                </div>

                <div class="ace-settings-item">
                    <input type="checkbox" class="ace ace-checkbox-2 ace-save-state" id="ace-settings-navbar" autocomplete="off" />
                    <label class="lbl" for="ace-settings-navbar"> Fixed Navbar</label>
                </div>

                <div class="ace-settings-item">
                    <input type="checkbox" class="ace ace-checkbox-2 ace-save-state" id="ace-settings-sidebar" autocomplete="off" />
                    <label class="lbl" for="ace-settings-sidebar"> Fixed Sidebar</label>
                </div>

                <div class="ace-settings-item">
                    <input type="checkbox" class="ace ace-checkbox-2 ace-save-state" id="ace-settings-breadcrumbs" autocomplete="off" />
                    <label class="lbl" for="ace-settings-breadcrumbs"> Fixed Breadcrumbs</label>
                </div>

                <div class="ace-settings-item">
                    <input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-rtl" autocomplete="off" />
                    <label class="lbl" for="ace-settings-rtl"> Right To Left (rtl)</label>
                </div>

                <div class="ace-settings-item">
                    <input type="checkbox" class="ace ace-checkbox-2 ace-save-state" id="ace-settings-add-container" autocomplete="off" />
                    <label class="lbl" for="ace-settings-add-container">
                        Inside
                        <b>.container</b>
                    </label>
                </div>
            </div><!-- /.pull-left -->

            <div class="pull-left width-50">
                <div class="ace-settings-item">
                    <input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-hover" autocomplete="off" />
                    <label class="lbl" for="ace-settings-hover"> Submenu on Hover</label>
                </div>

                <div class="ace-settings-item">
                    <input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-compact" autocomplete="off" />
                    <label class="lbl" for="ace-settings-compact"> Compact Sidebar</label>
                </div>

                <div class="ace-settings-item">
                    <input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-highlight" autocomplete="off" />
                    <label class="lbl" for="ace-settings-highlight"> Alt. Active Item</label>
                </div>
            </div><!-- /.pull-left -->
        </div><!-- /.ace-settings-box -->
    </div><!-- /.ace-settings-container -->

    <div class="page-header">
        <h1>Главная панель</h1>
    </div><!-- /.page-header -->

    <div class="row">
        <div class="col-xs-12">
            <div class="row">
                <div class="space-6"></div>
                <div class="col-sm-6">
                    <div class="tabbable">
                        <?= $tabs; ?>
                    </div>
                </div><!-- /.col -->

                <div class="vspace-12-sm"></div>

                <div class="col-sm-5">
                    <div class="widget-box">
                        <div class="widget-header widget-header-flat widget-header-small">
                            <h5 class="widget-title">
                                Текущий прогноз погоды в <?= $content['city']; ?>
                            </h5>
                        </div>

                        <div class="widget-body">
                            <div class="widget-main">
                                <div class="clearfix">
                                    <?= $content['html']; ?>
                                </div>
                                <div class="hr hr8 hr-double"></div>
                                <div class="clearfix">
                                    <div id="forecast_icon"></div>
                                </div>
                                <div class="hr hr8 hr-double"></div>
                            </div><!-- /.widget-main -->
                        </div><!-- /.widget-body -->
                    </div><!-- /.widget-box -->
                </div><!-- /.col -->
            </div><!-- /.row -->

            <div class="hr hr32 hr-dotted"></div>

            <div class="row">
                <div class="col-sm-5">
                    <div class="widget-box transparent">
                        <div class="widget-header widget-header-flat">
                            <h4 class="widget-title lighter">
                                <i class="ace-icon fa fa-star orange"></i>
                                Popular Domains
                            </h4>

                            <div class="widget-toolbar">
                                <a href="#" data-action="collapse">
                                    <i class="ace-icon fa fa-chevron-up"></i>
                                </a>
                            </div>
                        </div>

                        <div class="widget-body">
                            <div class="widget-main no-padding">
                                <table class="table table-bordered table-striped">
                                    <thead class="thin-border-bottom">
                                    <tr>
                                        <th>
                                            <i class="ace-icon fa fa-caret-right blue"></i>name
                                        </th>

                                        <th>
                                            <i class="ace-icon fa fa-caret-right blue"></i>price
                                        </th>

                                        <th class="hidden-480">
                                            <i class="ace-icon fa fa-caret-right blue"></i>status
                                        </th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    <tr>
                                        <td>internet.com</td>

                                        <td>
                                            <small>
                                                <s class="red">$29.99</s>
                                            </small>
                                            <b class="green">$19.99</b>
                                        </td>

                                        <td class="hidden-480">
                                            <span class="label label-info arrowed-right arrowed-in">on sale</span>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>online.com</td>

                                        <td>
                                            <b class="blue">$16.45</b>
                                        </td>

                                        <td class="hidden-480">
                                            <span class="label label-success arrowed-in arrowed-in-right">approved</span>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>newnet.com</td>

                                        <td>
                                            <b class="blue">$15.00</b>
                                        </td>

                                        <td class="hidden-480">
                                            <span class="label label-danger arrowed">pending</span>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>web.com</td>

                                        <td>
                                            <small>
                                                <s class="red">$24.99</s>
                                            </small>
                                            <b class="green">$19.95</b>
                                        </td>

                                        <td class="hidden-480">
																	<span class="label arrowed">
																		<s>out of stock</s>
																	</span>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>domain.com</td>

                                        <td>
                                            <b class="blue">$12.00</b>
                                        </td>

                                        <td class="hidden-480">
                                            <span class="label label-warning arrowed arrowed-right">SOLD</span>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div><!-- /.widget-main -->
                        </div><!-- /.widget-body -->
                    </div><!-- /.widget-box -->
                </div><!-- /.col -->

                <div class="col-sm-7">
                    <div class="widget-box transparent">
                        <div class="widget-header widget-header-flat">
                            <h4 class="widget-title lighter">
                                <i class="ace-icon fa fa-signal"></i>
                                Sale Stats
                            </h4>

                            <div class="widget-toolbar">
                                <a href="#" data-action="collapse">
                                    <i class="ace-icon fa fa-chevron-up"></i>
                                </a>
                            </div>
                        </div>

                        <div class="widget-body">
                            <div class="widget-main padding-4">
                                <div id="sales-charts"></div>
                            </div><!-- /.widget-main -->
                        </div><!-- /.widget-body -->
                    </div><!-- /.widget-box -->
                </div><!-- /.col -->
            </div><!-- /.row -->

            <div class="hr hr32 hr-dotted"></div>

            <div class="row">
            </div><!-- /.row -->

            <!-- PAGE CONTENT ENDS -->
        </div><!-- /.col -->
    </div><!-- /.row -->
<?php
$js = <<<JS
 $(document).ready(function(){
     $.ajax({
     url: '/main/default/forecast',
     type: 'POST',
     data: {'get_data':'get_data'},
     success: function(res){
     //alert("Сервер вернул вот что: " + res);
         $("#forecast_icon").empty();
         $("#forecast_icon").html(res);
     },
     error: function(){
     alert('Error!');
     }
     });
     var celsio = new JustGage({
        id: "celsio",
        formatNumber: true,
        gaugeWidthScale: 0.6,
      customSectors: [{
        color : "#2A95DF",
        lo : 0,
        hi : 17
      },{
        color : "#00ff00",
        lo : 18,
        hi : 30
      },{
        color : "#ff0000",
        lo : 31,
        hi : 100
      }],
        counter: true,
        title: "Температура",
        label: "C"
      });
     
     var humidity = new JustGage({
        id: "humidity",
        formatNumber: true,
        gaugeWidthScale: 0.6,
      customSectors: [{
        color : "#ffff00",
        lo : 0,
        hi : 50
      },{
        color : "#00ff00",
        lo : 51,
        hi : 70
      },{
        color : "#ff0000",
        lo : 71,
        hi : 100
      }],
        counter: true,
        title: "Влажность",
        label: "%"
      });
 });

JS;

$this->registerJs($js);
?>
