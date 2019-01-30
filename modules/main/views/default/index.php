<?php

//use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->registerJsFile('/js/justgage.js',
    ['depends' => ['yii\web\JqueryAsset']]);
$this->registerJsFile('/js/mqttws31.js',
    ['depends' => ['yii\web\JqueryAsset']]);
//$this->registerJsFile('/js/gChart.js'); //https://www.gstatic.com/charts/loader.js

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
                                        <?= $state; ?>
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
                                Климат-контроль
                            </h4>
                        </div>
                        <div id="chart-main"></div>
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
            var ptopic = $(this).parent().prev().attr('name');
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
     
    /*$("#from, #to").datepicker({
        dateFormat: "yy-mm-dd",
		firstDay: 1,   
    });*/
    
    $.ajax({
     url: '/main/default/chart',
     type: 'POST',
     data: {'type':'line'},
         success: function(res){
         //alert("Сервер вернул вот что: " + res);
         $("#chart-main").empty();
             Morris.Line({
                  element: 'chart-main',
                  data: JSON.parse(res),
                  xkey: 'd',
                  ykeys: ['t','h'],
                  labels: ['Температура, С','Влажность, %']
              });
        }
    });
     /*setInterval(function() {
        //$(".tabbable").load("/main/default/update-vars");
        //alert('timer');
    }, 5000);*/
     
     $('.easy-pie-chart.percentage').each(function(){
					$(this).easyPieChart({
						barColor: $(this).data('color'),
						trackColor: '#EEEEEE',
						scaleColor: false,
						lineCap: 'butt',
						lineWidth: 8,
						animate: ace.vars['old_ie'] ? false : 1000,
						size:54
					}).css('color', $(this).data('color'));
				});
JS;

$this->registerJs($js);
?>
