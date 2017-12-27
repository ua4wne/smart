<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->registerJsFile('/js/justgage.js',
    ['depends' => ['yii\web\JqueryAsset']]);
$this->registerJsFile('/js/mqttws31.js',
    ['depends' => ['yii\web\JqueryAsset']]);
$this->registerJsFile('/js/gCharts.js'); //https://www.gstatic.com/charts/loader.js

$this->title = 'Панель управления';
$this->params['breadcrumbs'][] = $this->title;
?>

    <div class="page-header">
        <h1>Главная панель</h1>
    </div><!-- /.page-header -->

    <div class="row">
        <div class="col-xs-12">
            <div class="row">
                <div class="col-md-5">
                    <div class="col-md-12">
                        <div class="tabbable">
                            <?= $tabs; ?>
                        </div>
                    </div><!-- /.col -->
                    <div class="clearfix"></div>
                    <div class="col-md-12">
                        <div class="widget-box transparent">
                            <div class="widget-header widget-header-flat">
                                <h4 class="widget-title lighter">
                                    <i class="ace-icon fa fa-wifi orange"></i>
                                    WiFi контроллеры
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
                                                <i class="ace-icon fa fa-caret-right blue"></i>Наименование
                                            </th>

                                            <th>
                                                <i class="ace-icon fa fa-caret-right blue"></i>Уровень сигнала
                                            </th>

                                            <th class="hidden-480">
                                                <i class="ace-icon fa fa-caret-right blue"></i>Батарея
                                            </th>
                                            <th class="hidden-480">
                                                <i class="ace-icon fa fa-caret-right blue"></i>Статус
                                            </th>
                                        </tr>
                                        </thead>

                                        <tbody>
                                        <tr>
                                            <td>SONOFF</td>

                                            <td><i class="ace-icon fa fa-signal blue"></i></td>

                                            <td><i class="ace-icon fa fa-battery-full blue"></i></td>

                                            <td class="hidden-480">
                                                <span class="label label-success arrowed-right arrowed-in">online</span>
                                            </td>
                                        </tr>

                                        </tbody>
                                    </table>
                                </div><!-- /.widget-main -->
                            </div><!-- /.widget-body -->
                        </div><!-- /.widget-box -->
                    </div><!-- /.col -->
                </div>

                <div class="col-md-7">
                    <div class="widget-box transparent">
                        <div class="widget-header widget-header-flat">

                            <h4 class="widget-title lighter">
                                <i class="ace-icon fa fa-bar-chart"></i>
                                Графики
                            </h4>
                            <span class="pull-right">
                                        <button class="btn btn-white btn-info dropdown-toggle" data-toggle="dropdown">
                                        Период
                                        <i class="ace-icon fa fa-chevron-down icon-on-right"></i>
                                    </button>

                                    <ul class="dropdown-menu dropdown-menu-right dropdown-caret dropdown-close">
                                        <li>
                                            <a href="#">Текущий день</a>
                                        </li>

                                        <li>
                                            <a href="#">Текущий месяц</a>
                                        </li>

                                        <li>
                                            <a href="#">Текущий год</a>
                                        </li>
                                        <li>
                                            <a href="#">Другой период</a>
                                        </li>

                                        <li class="divider"></li>

                                        <li>
                                            <a href="#">По умолчанию</a>
                                        </li>
                                    </ul>
                                    </span>
                        </div>
                        <div id="chart"></div>
                    </div><!-- /.widget-box -->
                </div><!-- /.col -->
            </div><!-- /.row -->

            <div class="hr hr32 hr-dotted"></div>

            <div class="row">
                <div class="col-md-5">
                    <div class="widget-box transparent">
                        <div class="widget-header widget-header-flat">
                            <h4 class="widget-title lighter">
                                <i class="ace-icon fa fa-cloud blue"></i>
                                Текущий прогноз погоды в <?= $content['city']; ?>
                            </h4>

                            <div class="widget-toolbar">
                                <a href="#" data-action="collapse">
                                    <i class="ace-icon fa fa-chevron-up"></i>
                                </a>
                            </div>
                        </div>

                        <div class="widget-body">
                            <div class="widget-main no-padding">
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
                            </div><!-- /.widget-main -->
                        </div><!-- /.widget-body -->
                    </div><!-- /.widget-box -->
                </div><!-- /.col -->

                <div class="col-md-7">
                    <div class="widget-box transparent">
                        <div class="widget-header widget-header-flat">
                            <h4 class="widget-title lighter">
                                <i class="ace-icon fa fa-envelope-o orange2"></i>
                                <a href="/main/syslog/index">Системный журнал</a>
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
                                            <i class="ace-icon fa fa-caret-right blue"></i>Тип
                                        </th>

                                        <th>
                                            <i class="ace-icon fa fa-caret-right blue"></i>Сообщение
                                        </th>

                                        <th class="hidden-480">
                                            <i class="ace-icon fa fa-caret-right blue"></i>Дата
                                        </th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    <?= $syslog; ?>
                                    </tbody>
                                </table>
                            </div><!-- /.widget-main -->
                        </div><!-- /.widget-body -->
                    </div><!-- /.widget-box -->
                </div><!-- /.col -->
            </div><!-- /.row -->

            <div class="hr hr32 hr-dotted"></div>

            <!-- PAGE CONTENT ENDS -->
        </div><!-- /.col -->
    </div><!-- /.row -->
<?php
$js = <<<JS
     var mqtt;
     var reconnectTimeout = 2000;
     var username;
     var password;
     var host;
     var port;
     var topic = '#';
     var clid = "web_" + parseInt(Math.random() * 100, 10);
     var useTLS = false;
     
     function MQTTconnect() {
        mqtt = new Paho.MQTT.Client(host,Number(port),clid);
        options = {
            timeout: 3,
            useSSL: useTLS,
            cleanSession: true,
            onSuccess: onConnect,
            onFailure: function (message) {
                alert('MQTT connect:' + message.errorMessage);
                setTimeout(MQTTconnect, reconnectTimeout);
            }
        };

        mqtt.onConnectionLost = onConnectionLost;
        mqtt.onMessageArrived = onMessageArrived;

        if (username != null) {
            options.userName = username;
            options.password = password;
        }
        mqtt.connect(options);
    }
    
    function onConnect() {
        // Connection succeeded; subscribe to our topic
        mqtt.subscribe(topic, {qos: 0});
    }
    
    function onConnectionLost(responseObject) {
        setTimeout(MQTTconnect, reconnectTimeout);
        alert(responseObject.errorMessage);
    };
     
     function onMessageArrived(message) {
        var topic = message.destinationName;
        var payload = message.payloadString;
        var route = 'NO';
        
        $.date = function(){
            return new Date().toLocaleString();
        };
                
        $.ajax({
            type: "POST",
            data: {topic:topic,payload:payload,route:route},
            url: "/main/config/mqttmsg",
            // success - это обработчик удачного выполнения событий
            success: function(resp) {
                //alert('topic is resieved');
            }
        });
        //route='NO';
    };
     
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
     
     $.ajax({
        type: "POST",
        data: {param:'conf'},
        url: "/main/config/mqttconf",
        // success - это обработчик удачного выполнения событий
        success: function(res) {
            //alert("Сервер вернул вот что: " + res);
            if (res && res.length > 0) {
                var obj = jQuery.parseJSON(res);
                username = obj.login;
                password = obj.pass;
                host = obj.server;
                port = obj.port;
            }
            if (username != null && password != null && host != null && port != null){
                MQTTconnect();
                //alert('Connected to ' + host + ':' + port);
            }
        },
        error: function () {
            alert('Error');
        }
    });
     
     $(document).on ({
        click: function() {
            var stat = $(this).prop('checked');
            var ptopic = $(this).prev().attr('name');
            if(stat){            
                var pval = '1';
            }
            else{
                var pval = '0';
            } 
            var message = new Paho.MQTT.Message(pval);
            message.destinationName = ptopic;
            message.qos = 0;
            route='public';
            mqtt.send(message);
        }
    }, ".ace-switch" );
     
    $("#from, #to").datepicker({
        dateFormat: "yy-mm-dd",
		firstDay: 1,   
    });
    
    google.charts.load('current', {'packages':['line']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {

      var data = new google.visualization.DataTable();
      data.addColumn('number', 'Дата');
      data.addColumn('number', 'Ванная');
      data.addColumn('number', 'Комната');
      data.addColumn('number', 'Кухня');

      data.addRows([
        [1,  37.8, 80.8, 41.8],
        [2,  30.9, 69.5, 32.4],
        [3,  25.4,   57, 25.7],
        [4,  11.7, 18.8, 10.5],
        [5,  11.9, 17.6, 10.4],
        [6,   8.8, 13.6,  7.7],
        [7,   7.6, 12.3,  9.6],
        [8,  12.3, 29.2, 10.6],
        [9,  16.9, 42.9, 14.8],
        [10, 12.8, 30.9, 11.6],
        [11,  5.3,  7.9,  4.7],
        [12,  6.6,  8.4,  5.2],
        [13,  4.8,  6.3,  3.6],
        [14,  4.2,  6.2,  3.4]
      ]);

      var options = {
        chart: {
          title: 'Графики температур в помещениях',
          subtitle: 'в градусах Цельсия'
        },
        //width: 900,
        height: 400
      };

      var chart = new google.charts.Line(document.getElementById('chart'));

      chart.draw(data, google.charts.Line.convertOptions(options));
    }
     
     /*setInterval(function() {
        //$(".tabbable").load("/main/default/update-vars");
        //alert('timer');
    }, 5000);*/
JS;

$this->registerJs($js);
?>
