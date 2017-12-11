$(document).ready(function(){
    //var prid='';
    $('#danger_msg').hide();
    $('#success_msg').hide();

    $(".fa").hover(function() {
        $(this).css('cursor','pointer');
    }, function() {
        $(this).css('cursor','auto');
    });

    $(".sub").hover(function() {
        $(this).css('cursor','pointer');
    }, function() {
        $(this).css('cursor','auto');
    });

    $(".pub").hover(function() {
        $(this).css('cursor','pointer');
    }, function() {
        $(this).css('cursor','auto');
    });


    var mqtt;
    var reconnectTimeout = 2000;
    var username = $('#mlogin').val();
    var password = $('#mpass').val();
    var host = $('#mserver').val();
    var port = $('#mport').val();
    var topic = '#';
    var clid = "web_" + parseInt(Math.random() * 100, 10);
    var useTLS = false;
    //var cleansession = true;
    var options;
    var route='NO';

    function MQTTconnect() {
        mqtt = new Paho.MQTT.Client(host,Number(port),clid);
        options = {
            timeout: 3,
            useSSL: useTLS,
            cleanSession: true,
            onSuccess: onConnect,
            onFailure: function (message) {
                $('#danger_msg').html("Connection failed: " + message.errorMessage + "Retrying");
                setTimeout(MQTTconnect, reconnectTimeout);
                $('#danger_msg').show();
                $('#success_msg').hide();
            }
        };

        mqtt.onConnectionLost = onConnectionLost;
        mqtt.onMessageArrived = onMessageArrived;

        if (username != null) {
            options.userName = username;
            options.password = password;
        }
        //alert("Host="+ host + ", port=" + port + " username=" + username + " password=" + password);
        mqtt.connect(options);
    }

    $(window).load(function() {
        MQTTconnect();
    });

    function onConnect() {
        $('#success_msg').html('Connected to ' + host + ':' + port);
        $('#success_msg').show();
        $('#danger_msg').hide();
        // Connection succeeded; subscribe to our topic
        mqtt.subscribe(topic, {qos: 0});
        //$('#topic').val(topic);
        $('#subs').prepend('<li class="sub"><pre>' + topic + '</pre></li>');
    }

    function onConnectionLost(responseObject) {
        setTimeout(MQTTconnect, reconnectTimeout);
        $('#danger_msg').html("connection lost: " + responseObject.errorMessage + ". Reconnecting");
        $('#danger_msg').show();
        $('#success_msg').hide();
    };

    function onMessageArrived(message) {
        var topic = message.destinationName;
        var payload = message.payloadString;
        var strval = topic + ' = ' + payload;
        var idx = "li.lws:contains(" + topic + ")";
        var count = $(idx).size();
        //alert('idx='+count);
        $.date = function(){
            return new Date().toLocaleString();
        };
        var ndate=$.date().replace(',','');
        strval += '\t(' + ndate + ')';
        if(count)
            $(idx).remove();
        $('#ws').prepend('<li class="lws"><pre>' + strval + '</pre></li>');
        $.ajax({
            type: "POST",
            data: {topic:topic,payload:payload,route:route},
            url: "/ajax?type=mqttmsg",
            // success - это обработчик удачного выполнения событий
            success: function(resp) {
                //alert("Сервер вернул вот что: " + resp);
                if(resp=="ERR")
                    alert('Ошибка записи в БД!');
            }
        });
        route='NO';
    };

    $("#set-topic").click(function(e) {
        e.preventDefault();
        if(!$('#name').val()){
            if($('#route').val()=='public')
                alert("Не указан топик для публикации!");
            else
                alert("Не указан топик для подписки!");
            $('#name').focus();
        }
        else if(!$('#payload').val() && $('#route').val()=='public'){
            alert("Не указано значение топика для публикации!");
            $('#payload').focus();
        }
        if($('#route').val()=='public'){
            var ptopic = $('#name').val();
            var pval = $('#payload').val();
            var message = new Paho.MQTT.Message(pval);
            message.destinationName = ptopic;
            message.qos = 0;
            route='public';
            mqtt.send(message);
            var idx = "li.pub:contains(" + ptopic + ")";
            var count = $(idx).size();
            if(count)
                $(idx).remove();
            $('#publ').prepend('<li class="pub"><pre>' + ptopic + '</pre></li>');
            $('#payload').val('');
        }
        if($('#route').val()=='subscribe'){
            var stopic = $('#name').val();
            var payload = '';
            mqtt.subscribe(stopic);
            route='subscribe';
            var idx = "li.sub:contains(" + stopic + ")";
            var count = $(idx).size();
            if(count)
                $(idx).remove();
            $('#subs').prepend('<li class="sub"><pre>' + stopic + '</pre></li>');
            /*$.ajax({
                type: "POST",
                data: {topic:stopic,payload:payload,route:route},
                url: "/ajax?type=mqttmsg",
                // success - это обработчик удачного выполнения событий
                success: function(resp) {
                    //alert("Сервер вернул вот что: " + resp);
                    if(resp=="ERR")
                        alert('Ошибка записи в БД!');
                }
            });*/
            $('#name').val('');
            route='NO';
        }

    });


    $("#subscrbtn").click(function(e) {
        e.preventDefault();
        if(!$('#stopic').val()){
            alert("Не указан топик для подписки!");
            $('#stopic').focus();
        }
        else {
            var stopic = $('#stopic').val();
            var payload = '';
            mqtt.subscribe(stopic);
            route='subscribe';
            var idx = "li.sub:contains(" + stopic + ")";
            var count = $(idx).size();
            if(count)
                $(idx).remove();
            $('#subs').prepend('<li class="sub"><pre>' + stopic + '</pre></li>');
            $.ajax({
                type: "POST",
                data: {topic:stopic,payload:payload,route:route},
                url: "/ajax?type=mqttmsg",
                // success - это обработчик удачного выполнения событий
                success: function(resp) {
                    //alert("Сервер вернул вот что: " + resp);
                    if(resp=="ERR")
                        alert('Ошибка записи в БД!');
                }
            });
            $('#stopic').val('');
            route='NO';
        }
    });

    $("#unsbtn").click(function(e) {
        e.preventDefault();
        if(!$('#untopic').val()){
            alert("Не указан топик для прекращения подписки!");
            $('#untopic').focus();
        }
        else {
            var utopic = $('#untopic').val();
            var tip = 'subscribe';
            Unsubscribe(utopic,tip);
            $.ajax({
                type: "POST",
                data: {topic:utopic,route:'subscribe'},
                url: "/ajax?type=deltopic",
                // success - это обработчик удачного выполнения событий
                success: function(resp) {
                    //alert("Сервер вернул вот что: " + resp);
                    if(resp=="ERR")
                        alert('Ошибка удаления записи из БД!');
                }
            });
            route='NO';
        }
    });

    $("#unpub").click(function(e) {
        e.preventDefault();
        if(!$('#ptopic').val()){
            alert("Не указан топик для прекращения подписки!");
            $('#ptopic').focus();
        }
        else {
            var utopic = $('#ptopic').val();
            var tip = 'public';
            Unsubscribe(utopic,tip);
            $.ajax({
                type: "POST",
                data: {topic:utopic,route:'public'},
                url: "/ajax?type=deltopic",
                // success - это обработчик удачного выполнения событий
                success: function(resp) {
                    //alert("Сервер вернул вот что: " + resp);
                    if(resp=="ERR")
                        alert('Ошибка удаления записи из БД!');
                }
            });
            route='NO';
        }
    });

    $(document).on ({
        click: function() {
            var uid=$(this).text();
            $('#ptopic').val(uid);
        }
    }, ".pub" );

    $(document).on ({
        click: function() {
            var uid=$(this).text();
            $('#untopic').val(uid);
        }
    }, ".sub" );

    //функция отписки от топика
    function Unsubscribe(utopic,tip) {
        mqtt.unsubscribe(utopic);
    //    if(utopic=='#') {
    //        $('li.lws').remove();
    //        $('li.pub').remove();
    //        $('li.sub').remove();
    //       $('#ptopic').val('');
    //        $('#pval').val('');
    //    }
    //    else {
        if(tip=='public') {
            $('#ptopic').val('');
            $('#pval').val('');
            var idx = ".pub:contains(" + utopic + ")";
        }
        else {
            $('#untopic').val('');
            var idx = ".sub:contains(" + utopic + ")";
        }
            var count = $(idx).size();
            if(count)
                $(idx).remove();
    //    }
    }

});