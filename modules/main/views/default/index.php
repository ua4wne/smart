<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

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

                <div class="col-sm-7 infobox-container">
                    <div class="infobox infobox-green">
                        <div class="infobox-icon">
                            <i class="ace-icon fa fa-comments"></i>
                        </div>

                        <div class="infobox-data">
                            <span class="infobox-data-number">32</span>
                            <div class="infobox-content">comments + 2 reviews</div>
                        </div>

                        <div class="stat stat-success">8%</div>
                    </div>

                    <div class="infobox infobox-blue">
                        <div class="infobox-icon">
                            <i class="ace-icon fa fa-twitter"></i>
                        </div>

                        <div class="infobox-data">
                            <span class="infobox-data-number">11</span>
                            <div class="infobox-content">new followers</div>
                        </div>

                        <div class="badge badge-success">
                            +32%
                            <i class="ace-icon fa fa-arrow-up"></i>
                        </div>
                    </div>

                    <div class="infobox infobox-pink">
                        <div class="infobox-icon">
                            <i class="ace-icon fa fa-shopping-cart"></i>
                        </div>

                        <div class="infobox-data">
                            <span class="infobox-data-number">8</span>
                            <div class="infobox-content">new orders</div>
                        </div>
                        <div class="stat stat-important">4%</div>
                    </div>

                    <div class="infobox infobox-red">
                        <div class="infobox-icon">
                            <i class="ace-icon fa fa-flask"></i>
                        </div>

                        <div class="infobox-data">
                            <span class="infobox-data-number">7</span>
                            <div class="infobox-content">experiments</div>
                        </div>
                    </div>

                    <div class="infobox infobox-orange2">
                        <div class="infobox-chart">
                            <span class="sparkline" data-values="196,128,202,177,154,94,100,170,224"></span>
                        </div>

                        <div class="infobox-data">
                            <span class="infobox-data-number">6,251</span>
                            <div class="infobox-content">pageviews</div>
                        </div>

                        <div class="badge badge-success">
                            7.2%
                            <i class="ace-icon fa fa-arrow-up"></i>
                        </div>
                    </div>

                    <div class="infobox infobox-blue2">
                        <div class="infobox-progress">
                            <div class="easy-pie-chart percentage" data-percent="42" data-size="46">
                                <span class="percent">42</span>%
                            </div>
                        </div>

                        <div class="infobox-data">
                            <span class="infobox-text">traffic used</span>

                            <div class="infobox-content">
                                <span class="bigger-110">~</span>
                                58GB remaining
                            </div>
                        </div>
                    </div>

                    <div class="space-6"></div>

                    <div class="infobox infobox-green infobox-small infobox-dark">
                        <div class="infobox-progress">
                            <div class="easy-pie-chart percentage" data-percent="61" data-size="39">
                                <span class="percent">61</span>%
                            </div>
                        </div>

                        <div class="infobox-data">
                            <div class="infobox-content">Task</div>
                            <div class="infobox-content">Completion</div>
                        </div>
                    </div>

                    <div class="infobox infobox-blue infobox-small infobox-dark">
                        <div class="infobox-chart">
                            <span class="sparkline" data-values="3,4,2,3,4,4,2,2"></span>
                        </div>

                        <div class="infobox-data">
                            <div class="infobox-content">Earnings</div>
                            <div class="infobox-content">$32,000</div>
                        </div>
                    </div>

                    <div class="infobox infobox-grey infobox-small infobox-dark">
                        <div class="infobox-icon">
                            <i class="ace-icon fa fa-download"></i>
                        </div>

                        <div class="infobox-data">
                            <div class="infobox-content">Downloads</div>
                            <div class="infobox-content">1,205</div>
                        </div>
                    </div>
                </div>

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
 });

JS;

$this->registerJs($js);
?>
