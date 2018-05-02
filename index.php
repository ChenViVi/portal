<?php
require_once("func.php");
$mysqli=new mysqli($DB_HOST,$DB_USER,$DB_PASS,$DB_NAME,$DB_PORT);
$mysqli->set_charset("utf8");?>
<!DOCTYPE html>
<html lang="zh-cmn-Hans">
<head>
    <meta charset="UTF-8">
    <link rel="icon" href="images/favicon.ico" type="image/x-icon" />
    <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon" />
    <title><?php echo $TITLE?></title>
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/jquery-ui.js"></script>
    <link href="css/materialize.min.css" rel="stylesheet" type="text/css">
    <script src="js/materialize.min.js"></script>
    <link href="css/jquery.contextMenu.css" rel="stylesheet" type="text/css">
    <script src="js/jquery.contextMenu.js" type="text/javascript"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        #search-bar {
            margin:20px auto;
            background-color: #22252A;
            width:60%;
            border-radius:3px;
        }
        #tab-nav {
            min-height:450px; height:auto!important; height:450px;
            margin:20px auto;
            background-color: #22252A;
            width:80%;
            position: relative;
            top: 20px;
            border-radius:3px;
        }
        .website {
            background-color: #22252A;
            padding: 5px;
            border-radius:5px;
        }
        .input-field input[type=text]:focus + label {
            color: #eeeeee;
        }
        .input-field input[type=text]:focus {
            border-bottom: 1px solid #eeeeee !important;
            box-shadow: 0 1px 0 0 #eeeeee !important
        }
        .input-field {
            color:white;
        }
        [type="radio"].with-gap:checked + label:before {
            border-radius: 50%;
            border: 2px solid #eeeeee;
        }
        [type="radio"].with-gap:checked + label:after {
            border-radius: 50%;
            border: 2px solid #eeeeee;
            background-color: #eeeeee;
            z-index: 0;
            -webkit-transform: scale(.5);
            -moz-transform: scale(.5);
            -ms-transform: scale(.5);
            -o-transform: scale(.5);
            transform: scale(.5);
        }
        #search-btn {
            background-color: #2D3035;
        }
        .tabs .indicator {
            background-color: #ffffff;
        }
        .modal{
            background-color: #2D3035;
        }
        h4,a {
            color: #eeeeee;
        }
        .select-wrapper ul li>span {
            color: #eeeeee;
        }
        .select-wrapper ul li{
            background-color: #22252A;
        }
        .select-wrapper ul li>span:hover {
            color: black;
        }
        .select-wrapper ul li.selected>span{
            color: black;
        }
    </style>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.modal').modal();
            $('select').material_select();
            $('.tooltipped').tooltip();
            var body = $('body');
            var search_radios = $('#search-radios');
            var site_types = $("#site-types");
            var website_row = $(".website-row");
            var search_param = $("input[name='search-param']");
            function utf8_length(str) {
                var str_array = str.split("");
                var count = 0;
                for (var i = 0; i < str_array.length; i++){
                    if (/^[\u4E00-\u9FA5]+$/.test(str_array[i])) count = count + 1;
                    else count = count + 0.5;
                }
                return count;
            }
            function utf8_substring(str, length) {
                var str_array = str.split("");
                var count = 0;
                var result = "";
                for (var i = 0; i < str_array.length && count < length; i++){
                    if (/^[\u4E00-\u9FA5]+$/.test(str_array[i])) count = count + 1;
                    else count = count + 0.5;
                    result = result + str_array[i];
                }
                return result;
            }
            $('#search-bar').on('keydown',function(event){
                if(event.keyCode == 13){
                    window.open ($('input[name=group1]:checked').val() + search_param.val());
                    search_param.val("");
                }
            });
            $("#search-btn").click(function(){
                window.open ($('input[name=group1]:checked').val() + search_param.val());
                search_param.val("");
            });
            search_radios.sortable({
                start: function(event, ui) {
                    var start_pos = ui.item.index();
                    ui.item.data('start_pos', start_pos);
                },
                update: function (event, ui) {
                    ui.item.data('end_pos', ui.item.index());
                    var start_pos = ui.item.data('start_pos');
                    var end_pos = ui.item.index();
                    var start,end;
                    if (start_pos < end_pos){
                        start = search_radios.children().eq(end_pos).attr("data-id");
                        end = search_radios.children().eq(end_pos-1).attr("data-id");
                    }
                    else {
                        start = search_radios.children().eq(end_pos).attr("data-id");
                        end = search_radios.children().eq(end_pos+1).attr("data-id");
                    }
                    if (start != end){
                        $.ajax({
                            url:"admin/search_sort.php",
                            type:"post",
                            data:("start=" + start + "&end=" + end),
                            async:true,
                            dataType:'json',
                            success: function (response) {
                                Materialize.toast(response.msg, 3000);
                            },
                            error:function (jqXHR, textStatus, errorThrown) {
                                Materialize.toast("未知错误", 3000);
                            }
                        });
                    }
                }
            });
            search_radios.contextMenu({
                selector: '.radios-div',
                items: {
                    "add": {
                        name: "添加",
                        callback: function() {
                            $('#modal-add-search').modal('open');
                        }
                    },
                    "delete": {
                        name: "删除",
                        callback: function() {
                            var item = $(this);
                            var id = item.attr("data-id");
                            $.ajax({
                                url:"request/search_delete.php",
                                type:"post",
                                data:("id=" + id),
                                async:true,
                                dataType:'json',
                                success: function (response) {
                                    Materialize.toast(response.msg, 3000);
                                    if (response.status == 0){
                                        item.remove();
                                    }
                                },
                                error:function (jqXHR, textStatus, errorThrown) {
                                    Materialize.toast("未知错误", 3000);
                                }
                            });
                        }
                    },
                    "update": {
                        name: "编辑",
                        callback: function() {
                            var item = $(this);
                            var id = item.attr("data-id");
                            var name = item.children('label').text();
                            var url = item.children('input').val();
                            var modal = $('#modal-update-search');
                            var modal_content = modal.children('.modal-content');
                            modal_content.children('input').val(id);
                            modal_content.children('div').eq(0).children('input').val(name);
                            modal_content.children('div').eq(1).children('input').val(url);
                            Materialize.updateTextFields();
                            modal.modal('open');
                        }
                    }
                }
            });
            $("a.add-search").click(function(){
                var button = $(this);
                button.attr("disabled",true);
                button.attr("disabled","disabled");
                $.ajax({
                    url:"request/search_add.php",
                    type:"post",
                    data:$("form.add-search").serialize(),
                    async:true,
                    dataType:'json',
                    success: function (response) {
                        Materialize.toast(response.msg, 3000);
                        if (response.status == 0){
                            search_radios.append(
                                "<div class='col s2 radios-div' data-id='" + response.data.id + "'>" +
                                "<input class='with-gap' name='group1' type='radio' id='radio" + response.data.id + "' value='" + response.data.url + "'>" +
                                "<label class='grey-text text-lighten-3' for='radio" + response.data.id + "'>" + response.data.name + "</label>" +
                                "</div>"
                            );
                            var modal = $("#modal-add-search");
                            modal.modal('close');
                            var modal_content = modal.children('.modal-content');
                            modal_content.children('div').eq(0).children('input').val("");
                            modal_content.children('div').eq(1).children('input').val("");
                        }
                    },
                    error:function (jqXHR, textStatus, errorThrown) {
                        Materialize.toast("未知错误", 3000);
                    },
                    complete:function (jqXHR, textStatus, errorThrown) {
                        button.removeAttr("disabled");
                        button.attr("disabled",false);
                    }
                });
            });
            $("a.update-search").click(function(){
                var button = $(this);
                button.attr("disabled",true);
                button.attr("disabled","disabled");
                $.ajax({
                    url:"request/search_update.php",
                    type:"post",
                    data:$("form.update-search").serialize(),
                    async:true,
                    dataType:'json',
                    success: function (response) {
                        Materialize.toast(response.msg, 3000);
                        if (response.status == 0){
                            var item = $(".radios-div[data-id='" + response.data.id + "']")
                            item.children('label').text(response.data.name);
                            item.children('input').val(response.data.url);
                            $("#modal-update-search").modal('close');
                        }
                    },
                    error:function (jqXHR, textStatus, errorThrown) {
                        Materialize.toast("未知错误", 3000);
                    },
                    complete:function (jqXHR, textStatus, errorThrown) {
                        button.removeAttr("disabled");
                        button.attr("disabled",false);
                    }
                });
            });
            site_types.sortable({
                start: function(event, ui) {
                    var start_pos = ui.item.index();
                    ui.item.data('start_pos', start_pos);
                },
                update: function (event, ui) {
                    ui.item.data('end_pos', ui.item.index());
                    var start_pos = ui.item.data('start_pos');
                    var end_pos = ui.item.index();
                    var start,end;
                    if (start_pos < end_pos){
                        start = site_types.children().eq(end_pos).attr("data-id");
                        end = site_types.children().eq(end_pos-1).attr("data-id");
                    }
                    else {
                        start = site_types.children().eq(end_pos).attr("data-id");
                        end = site_types.children().eq(end_pos+1).attr("data-id");
                    }
                    if (start != end){
                        $.ajax({
                            url:"request/site_type_sort.php",
                            type:"post",
                            data:("start=" + start + "&end=" + end),
                            async:true,
                            dataType:'json',
                            success: function (response) {
                                Materialize.toast("排序成功", 3000);
                                if (response.status == 0){
                                    var tab_nav = $("#tab-nav");
                                    tab_nav.html(tab_nav.html());
                                    $('.tabs').tabs();
                                }
                            },
                            error:function (jqXHR, textStatus, errorThrown) {
                                Materialize.toast("未知错误", 3000);
                            }
                        });
                    }
                }
            });
            site_types.contextMenu({
                selector: 'li',
                items: {
                    "add": {
                        name: "添加",
                        callback: function() {
                            $('#modal-add-site-type').modal('open');
                        }
                    },
                    "delete": {
                        name: "删除",
                        callback: function() {
                            var item = $(this);
                            var id = item.attr("data-id");
                            var item_id;
                            var item_pre = item.prev();
                            if (item_pre.length == 0) item_id = item.next().attr("data-id");
                            else item_id = item.prev().attr("data-id");
                            $.ajax({
                                url:"request/site_type_delete.php",
                                type:"post",
                                data:("id=" + id),
                                async:true,
                                dataType:'json',
                                success: function (response) {
                                    Materialize.toast(response.msg, 3000);
                                    if (response.status == 0){
                                        item.remove();
                                        $('.tabs').tabs('select_tab', item_id);
                                    }
                                },
                                error:function (jqXHR, textStatus, errorThrown) {
                                    Materialize.toast("未知错误", 3000);
                                }
                            });
                        }
                    },
                    "update": {
                        name: "编辑",
                        callback: function() {
                            var item = $(this);
                            var id = item.attr("data-id");
                            var name = item.children().text();
                            var modal = $('#modal-update-site-type');

                            var modal_content = modal.children('.modal-content');
                            modal_content.children('input').val(id);
                            modal_content.children('div').eq(0).children('input').val(name);
                            Materialize.updateTextFields();
                            modal.modal('open');
                        }
                    }
                }
            });
            $("a.add-site-type").click(function(){
                var button = $(this);
                button.attr("disabled",true);
                button.attr("disabled","disabled");
                $.ajax({
                    url:"request/site_type_add.php",
                    type:"post",
                    data:$("form.add-site-type").serialize(),
                    async:true,
                    dataType:'json',
                    success: function (response) {
                        Materialize.toast(response.msg, 3000);
                        if (response.status == 0){
                            site_types.append("<li data-id='" + response.data.id + "' class='tab ui-sortable-handle'><a href='#" + response.data.id + "' class='grey-text text-lighten-3' style='text-transform: none !important'>" + response.data.name + "</a></li>");
                            $('.tabs').tabs();
                            var modal = $('#modal-add-site-type');
                            modal.modal('close');
                            var modal_content = modal.children('.modal-content');
                            modal_content.children('div').eq(0).children('input').val("");
                        }
                    },
                    error:function (jqXHR, textStatus, errorThrown) {
                        Materialize.toast("未知错误", 3000);
                    },
                    complete:function (jqXHR, textStatus, errorThrown) {
                        button.removeAttr("disabled");
                        button.attr("disabled",false);
                    }
                });
            });
            $("a.update-site-type").click(function(){
                var button = $(this);
                button.attr("disabled",true);
                button.attr("disabled","disabled");
                $.ajax({
                    url:"request/site_type_update.php",
                    type:"post",
                    data:$("form.update-site-type").serialize(),
                    async:true,
                    dataType:'json',
                    success: function (response) {
                        Materialize.toast(response.msg, 3000);
                        if (response.status == 0){
                            $("li.tab[data-id='"+ response.data.id +"']").children().text(response.data.name);
                            $("#modal-add-site-type").modal('close');
                        }
                    },
                    error:function (jqXHR, textStatus, errorThrown) {
                        Materialize.toast("未知错误", 3000);
                    },
                    complete:function (jqXHR, textStatus, errorThrown) {
                        button.removeAttr("disabled");
                        button.attr("disabled",false);
                    }
                });
            });
            website_row.sortable({
                start: function(event, ui) {
                    var start_pos = ui.item.index();
                    ui.item.data('start_pos', start_pos);
                },
                update: function (event, ui) {
                    ui.item.data('end_pos', ui.item.index());
                    var start_pos = ui.item.data('start_pos');
                    var end_pos = ui.item.index();
                    var start,end;
                    if (start_pos < end_pos){
                        start = $(this).children().eq(end_pos).attr("data-id");
                        end = $(this).children().eq(end_pos-1).attr("data-id");
                    }
                    else {
                        start = $(this).children().eq(end_pos).attr("data-id");
                        end = $(this).children().eq(end_pos+1).attr("data-id");
                    }
                    if (start != end){
                        var type_id = $(this).attr("id");
                        $.ajax({
                            url:"request/site_sort.php",
                            type:"post",
                            data:("start=" + start + "&end=" + end + "&type_id=" + type_id),
                            async:true,
                            dataType:'json',
                            success: function (response) {
                                Materialize.toast("排序成功", 3000);
                            },
                            error:function (jqXHR, textStatus, errorThrown) {
                                Materialize.toast("未知错误", 3000);
                            }
                        });
                    }
                }
            });
            $.contextMenu({
                selector: '.website-div',
                items: {
                    "add": {
                        name: "添加",
                        callback: function() {
                            var type_id = $(this).parent().attr("id");
                            $.ajax({
                                url:"request/site_type_get.php",
                                type:"post",
                                async:true,
                                dataType:'json',
                                success: function (response) {
                                    if (response.status == 0){
                                        var select = $("select[id='type_id']");
                                        select.html("");
                                        for(var i = 0; i < response.data.length; i++){
                                            if(response.data[i].id == type_id) select.append("<option selected value='" + response.data[i].id + "'>" + response.data[i].name + "</option>");
                                            else select.append("<option value='" + response.data[i].id + "'>" + response.data[i].name + "</option>");
                                        }
                                        $('select').material_select();
                                        $('#modal-add-site').modal('open');
                                    }
                                },
                                error:function (jqXHR, textStatus, errorThrown) {
                                    Materialize.toast("未知错误", 3000);
                                }
                            });
                        }
                    },
                    "delete": {
                        name: "删除",
                        callback: function() {
                            var item = $(this);
                            var id = item.attr("data-id");
                            $.ajax({
                                url:"request/site_delete.php",
                                type:"post",
                                data:("id=" + id),
                                async:true,
                                dataType:'json',
                                success: function (response) {
                                    Materialize.toast(response.msg, 3000);
                                    if (response.status == 0){
                                        item.remove();
                                    }
                                },
                                error:function (jqXHR, textStatus, errorThrown) {
                                    Materialize.toast("未知错误", 3000);
                                }
                            });
                        }
                    },
                    "update": {
                        name: "编辑",
                        callback: function() {
                            var item = $(this);
                            var item_a = item.children();
                            var id = item.attr("data-id");
                            var type_id = $(this).parent().attr("id");
                            var name = item.attr("data-tooltip");
                            var url = item_a.attr("href");
                            var modal = $('#modal-update-site');
                            $.ajax({
                                url:"request/site_type_get.php",
                                type:"post",
                                async:true,
                                dataType:'json',
                                success: function (response) {
                                    if (response.status == 0){
                                        var select = $("select[id='type_id']");
                                        select.html("");
                                        for(var i = 0; i < response.data.length; i++){
                                            if(response.data[i].id == type_id) select.append("<option selected value='" + response.data[i].id + "'>" + response.data[i].name + "</option>");
                                            else select.append("<option value='" + response.data[i].id + "'>" + response.data[i].name + "</option>");
                                        }
                                        $('select').material_select();
                                        var modal_content = modal.children('.modal-content');
                                        modal_content.children('input').val(id);
                                        modal_content.children('div').eq(0).children('input').val(name);
                                        modal_content.children('div').eq(2).children('input').val(url);
                                        Materialize.updateTextFields();
                                        modal.modal('open');
                                    }
                                },
                                error:function (jqXHR, textStatus, errorThrown) {
                                    Materialize.toast("未知错误", 3000);
                                }
                            });
                        }
                    }
                }
            });
            $.contextMenu({
                selector: '.website-row',
                items: {
                    "add": {
                        name: "添加",
                        callback: function() {
                            var type_id = $(this).attr("id");
                            $.ajax({
                                url:"request/site_type_get.php",
                                type:"post",
                                async:true,
                                dataType:'json',
                                success: function (response) {
                                    if (response.status == 0){
                                        var select = $("select[id='type_id']");
                                        select.html("");
                                        for(var i = 0; i < response.data.length; i++){
                                            if(response.data[i].id == type_id) select.append("<option selected value='" + response.data[i].id + "'>" + response.data[i].name + "</option>");
                                            else select.append("<option value='" + response.data[i].id + "'>" + response.data[i].name + "</option>");
                                        }
                                        $('select').material_select();
                                        $('#modal-add-site').modal('open');
                                    }
                                },
                                error:function (jqXHR, textStatus, errorThrown) {
                                    Materialize.toast("未知错误", 3000);
                                }
                            });
                        }
                    }
                }
            });
            $("a.add-site").click(function(){
                var button = $(this);
                button.attr("disabled",true);
                button.attr("disabled","disabled");
                $.ajax({
                    url:"request/site_add.php",
                    type:"post",
                    data:$("form.add-site").serialize(),
                    async:true,
                    dataType:'json',
                    success: function (response) {
                        Materialize.toast(response.msg, 3000);
                        if (response.status == 0){
                            if (response.data.name.length <= 12){
                                $(".website-row[id='" + response.data.type_id + "']").append(
                                    "<div class='website-div tooltipped col s3' style='margin-top: 20px; display: block;' data-id='" + response.data.id + "' data-position='right' data-tooltip='" + response.data.name + "'>" +
                                    "<a href='" + response.data.url + "' target='_blank'>" +
                                    "<div class='website hoverable z-depth-2' style='position:relative;'>" +
                                    "<img src='http://favicon.byi.pw/?url=" + response.data.url + "' width='16px' style='position: absolute;top: 50%;transform: translateY(-50%);'>" +
                                    "<p class='grey-text text-lighten-3 center'>" + response.data.name + "</p>" +
                                    "</div>" +
                                    "</a>" +
                                    "</div>"
                                );
                            }
                            else {
                                $(".website-row[id='" + response.data.type_id + "']").append(
                                    "<div class='website-div tooltipped col s3' style='margin-top: 20px; display: block;' data-id='" + response.data.id + "' data-position='right' data-tooltip='" + response.data.name + "'>" +
                                    "<a href='" + response.data.url + "' target='_blank'>" +
                                    "<div class='website hoverable z-depth-2' style='position:relative;'>" +
                                    "<img src='http://favicon.byi.pw/?url=" + response.data.url + "' width='16px' style='position: absolute;top: 50%;transform: translateY(-50%);'>" +
                                    "<p class='grey-text text-lighten-3 center'>" + response.data.name.substring(0,11) + "...</p>" +
                                    "</div>" +
                                    "</a>" +
                                    "</div>"
                                );
                            }
                            $('.tooltipped').tooltip();
                            var modal = $('#modal-add-site');
                            modal.modal('close');
                            var modal_content = modal.children('.modal-content');
                            modal_content.children('div').eq(0).children('input').val("");
                            modal_content.children('div').eq(2).children('input').val("");
                        }
                    },
                    error:function (jqXHR, textStatus, errorThrown) {
                        Materialize.toast("未知错误", 3000);
                    },
                    complete:function (jqXHR, textStatus, errorThrown) {
                        button.removeAttr("disabled");
                        button.attr("disabled",false);
                    }
                });
            });
            $("a.update-site").click(function(){
                var button = $(this);
                button.attr("disabled",true);
                button.attr("disabled","disabled");
                $.ajax({
                    url:"request/site_update.php",
                    type:"post",
                    data:$("form.update-site").serialize(),
                    async:true,
                    dataType:'json',
                    success: function (response) {
                        Materialize.toast(response.msg, 3000);
                        if (response.status == 0){
                            var item = $(".website-div[data-id='" + response.data.id + "']");
                            var type_id = item.parent().attr("id");
                            if (response.data.type_id != type_id) {
                                item.remove();
                                if (utf8_length(response.data.name) <= 12){
                                    $(".website-row[id='" + response.data.type_id + "']").append(
                                        "<div class='website-div tooltipped col s3' style='margin-top: 20px; display: block;' data-id='" + response.data.id + "' data-position='right' data-tooltip='" + response.data.name + "'>" +
                                        "<a href='" + response.data.url + "' target='_blank'>" +
                                        "<div class='website hoverable z-depth-2' style='position:relative;'>" +
                                        "<img src='http://favicon.byi.pw/?url=" + response.data.url + "' width='16px' style='position: absolute;top: 50%;transform: translateY(-50%);'>" +
                                        "<p class='grey-text text-lighten-3 center'>" + response.data.name + "</p>" +
                                        "</div>" +
                                        "</a>" +
                                        "</div>"
                                    );
                                }
                                else {
                                    $(".website-row[id='" + response.data.type_id + "']").append(
                                        "<div class='website-div tooltipped col s3' style='margin-top: 20px; display: block;' data-id='" + response.data.id + "' data-position='right' data-tooltip='" + response.data.name + "'>" +
                                        "<a href='" + response.data.url + "' target='_blank'>" +
                                        "<div class='website hoverable z-depth-2' style='position:relative;'>" +
                                        "<img src='http://favicon.byi.pw/?url=" + response.data.url + "' width='16px' style='position: absolute;top: 50%;transform: translateY(-50%);'>" +
                                        "<p class='grey-text text-lighten-3 center'>" + utf8_substring(response.data.name,11) + "...</p>" +
                                        "</div>" +
                                        "</a>" +
                                        "</div>"
                                    );
                                }
                                $('.tooltipped').tooltip();
                            }
                            else {
                                var item_a = item.children();
                                var item_p = item_a.children().children('p');
                                if (utf8_length(response.data.name) <= 12) item_p.text(response.data.name);
                                else item_p.text(utf8_substring(response.data.name,11));
                                item.attr("data-tooltip", response.data.name);
                                item_a.attr("href", response.data.url);
                            }
                            $("#modal-update-site").modal('close');
                        }
                    },
                    error:function (jqXHR, textStatus, errorThrown) {
                        Materialize.toast("未知错误", 3000);
                    },
                    complete:function (jqXHR, textStatus, errorThrown) {
                        button.removeAttr("disabled");
                        button.attr("disabled",false);
                    }
                });
            });
        });
    </script>
</head>
<?php
$stmt=$mysqli->prepare("SELECT * FROM bg ORDER BY rand() limit 1");
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
?>
<body style="background-color:#2D3035;">
<form class="add-search">
    <div id="modal-add-search" class="modal">
        <div class="modal-content">
            <h4 class="grey-text">添加搜索引擎</h4>
            <div class="input-field">
                <input name="name" id="name" type="text" class="validate" autocomplete="off">
                <label for="name">名称&nbsp;例如：百度</label>
            </div>
            <div class="input-field">
                <input name="url" id="url" type="text" class="validate" autocomplete="off">
                <label for="url">链接地址&nbsp;例如：baidu.com/s?wd=</label>
            </div>
        </div>
        <div class="modal-footer" style="background-color:#2D3035;">
            <a class="modal-action modal-close waves-effect btn-flat grey-text text-lighten-3">取消</a>
            <a class="add-search waves-effect btn-flat grey-text text-lighten-3">确定</a>
        </div>
    </div>
</form>
<form class="update-search">
    <div id="modal-update-search" class="modal">
        <div class="modal-content">
            <h4>修改搜索引擎</h4>
            <input type="hidden" id="id" name="id" value="" autocomplete="off"/>
            <div class="input-field">
                <input name="name" id="name" type="text" class="validate" value="" autocomplete="off">
                <label for="name">名称</label>
            </div>
            <div class="input-field">
                <input name="url" id="url" type="text" class="validate" value="" autocomplete="off">
                <label for="url">链接地址</label>
            </div>
        </div>
        <div class="modal-footer" style="background-color:#2D3035;">
            <a class="modal-action modal-close waves-effect btn-flat grey-text text-lighten-3">取消</a>
            <a class="update-search waves-effect btn-flat grey-text text-lighten-3">确定</a>
        </div>
    </div>
</form>
<form class="add-site-type">
    <div id="modal-add-site-type" class="modal">
        <div class="modal-content">
            <h4>添加网站分类</h4>
            <div class="input-field">
                <input name="name" id="name" type="text" class="validate" autocomplete="off">
                <label for="name">名称</label>
            </div>
        </div>
        <div class="modal-footer" style="background-color:#2D3035;">
            <a class="modal-action modal-close waves-effect btn-flat grey-text text-lighten-3">取消</a>
            <a class="add-site-type waves-effect btn-flat grey-text text-lighten-3">确定</a>
        </div>
    </div>
</form>
<form class="update-site-type" method="post">
    <div id="modal-update-site-type" class="modal">
        <div class="modal-content">
            <h4>修改搜索引擎</h4>
            <input type="hidden" id="id" name="id" value="" autocomplete="off"/>
            <div class="input-field">
                <input name="name" id="name" type="text" class="validate" value="" autocomplete="off">
                <label for="name">名称</label>
            </div>
        </div>
        <div class="modal-footer" style="background-color:#2D3035;">
            <a class="modal-action modal-close waves-effect btn-flat grey-text text-lighten-3">取消</a>
            <a class="update-site-type waves-effect btn-flat grey-text text-lighten-3">确定</a>
        </div>
    </div>
</form>
<form class="add-site">
    <div id="modal-add-site" class="modal">
        <div class="modal-content row">
            <h4>添加网站</h4>
            <div class="input-field col s6">
                <input name="name" id="name" type="text" class="validate" autocomplete="off">
                <label for="name">名称&nbsp;例如：百度</label>
            </div>
            <div class="input-field col s6">
                <select id="type_id" name="type_id">
                </select>
                <label for="type_id">网站类别</label>
            </div>
            <div class="input-field col s12">
                <input name="url" id="url" type="text" class="validate" autocomplete="off">
                <label for="url">链接地址&nbsp;例如：www.baidu.com</label>
            </div>
        </div>
        <div class="modal-footer" style="background-color:#2D3035;">
            <a class="modal-action modal-close waves-effect btn-flat grey-text text-lighten-3">取消</a>
            <a class="add-site waves-effect btn-flat grey-text text-lighten-3">确定</a>
        </div>
    </div>
</form>
<form class="update-site">
    <div id="modal-update-site" class="modal">
        <div class="modal-content row">
            <h4>修改网站</h4>
            <input type="hidden" id="id" name="id" value="" autocomplete="off"/>
            <div class="input-field col s6">
                <input name="name" id="name" type="text" class="validate" autocomplete="off">
                <label for="name">名称</label>
            </div>
            <div class="input-field col s6">
                <select id="type_id" name="type_id">
                </select>
                <label for="type_id">网站类别</label>
            </div>
            <div class="input-field col s12">
                <input name="url" id="url" type="text" class="validate" autocomplete="off">
                <label for="url">链接地址</label>
            </div>
        </div>
        <div class="modal-footer" style="background-color:#2D3035;">
            <a class="modal-action modal-close waves-effect btn-flat grey-text text-lighten-3">取消</a>
            <a class="update-site waves-effect btn-flat grey-text text-lighten-3">确定</a>
        </div>
    </div>
</form>
<div id="search-bar" class="z-depth-1">
    <div class="row">
        <div class="row col s12" style="position:relative;">
            <div class="input-field col s11" >
                <input name="search-param" type="text" class="validate" autocomplete="off">
            </div>
            <a id="search-btn" class="waves-effect waves-light btn col s1" style="position: absolute;top: 50%;transform: translateY(-50%);"><i class="material-icons">search</i></a>
        </div>
        <div class="row col s12" id="search-radios">
            <?php
            $stmt=$mysqli->prepare("SELECT * FROM search ORDER BY id");
            $stmt->execute();
            $result = $stmt->get_result();
            $checked = true;
            while ($row = $result->fetch_assoc()) {
                if($checked){
                    echo "<div class=\"col s2 radios-div\"  data-id=\"" . $row['id'] . "\">"
                        . "<input checked class=\"with-gap\" name=\"group1\" type=\"radio\" id=\"radio" . $row['id'] . "\" value=\"" . $row['url'] . "\"/>"
                        . "<label class=\"grey-text text-lighten-3\" for=\"radio" . $row['id'] . "\">" . $row['name'] . "</label>"
                        . "</div>";
                    $checked = false;
                }
                else echo "<div class=\"col s2 radios-div\"  data-id=\"" . $row['id'] . "\">"
                            . "<input class=\"with-gap\" name=\"group1\" type=\"radio\" id=\"radio" . $row['id'] . "\" value=\"" . $row['url'] . "\"/>"
                            . "<label class=\"grey-text text-lighten-3\" for=\"radio" . $row['id'] . "\">" . $row['name'] . "</label>"
                            . "</div>";
           }?>
        </div>
    </div>
</div>
<div id="tab-nav" class="z-depth-1">
    <ul class="tabs transparent">
        <div id="site-types">
            <?php
            $stmt=$mysqli->prepare("SELECT * FROM site_type ORDER BY id");
            $stmt->execute();
            $result = $stmt->get_result();
            $site_type_ids = array();
            while ($row = $result->fetch_assoc()) {
                array_push($site_type_ids, $row['id']);
                ?>
                <li data-id="<?php echo $row['id'];?>" class="tab"><a href="#<?php echo $row['id']; ?>"  class="grey-text text-lighten-3" style="text-transform: none !important"><?php echo $row['name']; ?></a></li>
            <?php } ?>
        </div>
        <li class="indicator" style="right: 186px; left: 68px;"></li>
    </ul>
    <?php
    for ($i = 0; $i < count($site_type_ids); $i++){ ?>
        <div id="<?php echo $site_type_ids[$i] ?>" class="row website-row" style="min-height:450px; height:auto!important; height:450px;">
            <?php
            $stmt=$mysqli->prepare("SELECT * from site WHERE type_id = ?");
            $stmt->bind_param('i', $site_type_ids[$i]);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {?>
                <div class="website-div tooltipped col s3" style="margin-top: 20px; display: block;" data-id="<?php echo $row['id']; ?>" data-position="right" data-tooltip="<?php echo $row['name']; ?>">
                    <a href="<?php echo $row['url'] ?>" target="_blank">
                        <div class="website hoverable z-depth-2" style="position:relative;">
                            <img src="http://favicon.byi.pw/?url=<?php echo $row['url'] ?>" width="16px" style="position: absolute;top: 50%;transform: translateY(-50%);">
                            <p class="grey-text text-lighten-3 center"><?php if (utf8_length($row['name']) <= 12) echo $row['name']; else echo utf8_substring($row['name'], 11) . "..." ?></p>
                        </div>
                    </a>
                </div>
            <?php } ?>
        </div>
    <?php } ?>
    <?php mysqli_close($mysqli);?>
</div>
</body>
</html>