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
    <script src="js/materialize.min.js"></script>
    <script src="js/jquery.contextMenu.js" type="text/javascript"></script>
    <link href="css/materialize.min.css" rel="stylesheet" type="text/css">
    <link href="css/jquery.contextMenu.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        #search-bar{
            margin:20px auto;
            background-color: rgba(255,255,255,0.65);
            width:60%;
            border-radius:3px;
        }
        #tab-nav{
            min-height:450px; height:auto!important; height:450px;
            margin:20px auto;
            background-color: rgba(255,255,255,0.55);
            width:80%;
            position: relative;
            top: 20px;
            border-radius:3px;
        }
        .website{
            background-color: rgba(255,255,255,0.3);
            padding: 5px;
            border-radius:5px;
        }
    </style>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.modal').modal();
            $('select').material_select();
            var body = $('body');
            var search_radios = $('#search-radios');
            var site_types = $("#site-types");
            var website_row = $(".website-row");
            var search_param = $("input[name='search-param']");
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
                            $("a.update-search").click(function(){
                                $.ajax({
                                    url:"request/search_update.php",
                                    type:"post",
                                    data:$("form.update-search").serialize(),
                                    async:true,
                                    dataType:'json',
                                    success: function (response) {
                                        Materialize.toast(response.msg, 3000);
                                        if (response.status == 0){
                                            item.children('label').text(response.data.name);
                                            item.children('input').val(response.data.url);
                                        }
                                    },
                                    error:function (jqXHR, textStatus, errorThrown) {
                                        Materialize.toast("未知错误", 3000);
                                    }
                                });
                            });
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
                $.ajax({
                    url:"request/search_add_one.php",
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
                                "<label class='grey-text text-darken-3' for='radio" + response.data.id + "'>" + response.data.name + "</label>" +
                                "</div>"
                            );
                        }
                    },
                    error:function (jqXHR, textStatus, errorThrown) {
                        Materialize.toast("未知错误", 3000);
                    },
                    complete:function (jqXHR, textStatus, errorThrown) {
                        var modal_content = $('#modal-add-search').children('.modal-content');
                        modal_content.children('div').eq(0).children('input').val("");
                        modal_content.children('div').eq(1).children('input').val("");
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
                            var pre_item_id = item.prev().attr("data-id");
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
                                        $('.tabs').tabs('select_tab', pre_item_id);
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
                            $("a.update-site-type").click(function(){
                                $.ajax({
                                    url:"request/site_type_update.php",
                                    type:"post",
                                    data:$("form.update-site-type").serialize(),
                                    async:true,
                                    dataType:'json',
                                    success: function (response) {
                                        Materialize.toast(response.msg, 3000);
                                        if (response.status == 0){
                                            item.children().text(response.data.name);
                                        }
                                    },
                                    error:function (jqXHR, textStatus, errorThrown) {
                                        Materialize.toast("未知错误", 3000);
                                    }
                                });
                            });
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
                $.ajax({
                    url:"request/site_type_add_one.php",
                    type:"post",
                    data:$("form.add-site-type").serialize(),
                    async:true,
                    dataType:'json',
                    success: function (response) {
                        Materialize.toast(response.msg, 3000);
                        if (response.status == 0){
                            site_types.append("<li data-id='" + response.data.id + "' class='tab ui-sortable-handle'><a href='#" + response.data.id + "' class='teal-text active'>" + response.data.name + "</a></li>");
                            $('.tabs').tabs();
                        }
                    },
                    error:function (jqXHR, textStatus, errorThrown) {
                        Materialize.toast("未知错误", 3000);
                    },
                    complete:function (jqXHR, textStatus, errorThrown) {
                        var modal_content = $('#modal-add-site-type').children('.modal-content');
                        modal_content.children('div').eq(0).children('input').val("");
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
                                        $("#add_site_mult").attr("href", "site_add.php?id=" + type_id);
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
                            var item_p = item_a.children().children('p');
                            var id = item.attr("data-id");
                            var type_id = $(this).parent().attr("id");
                            var name = item_p.text();
                            var url = item_a.attr("href");
                            var modal = $('#modal-update-site');
                            $("a.update-site").click(function(){
                                $.ajax({
                                    url:"request/site_update.php",
                                    type:"post",
                                    data:$("form.update-site").serialize(),
                                    async:true,
                                    dataType:'json',
                                    success: function (response) {
                                        Materialize.toast(response.msg, 3000);
                                        if (response.status == 0){
                                            item_p.text(response.data.name);
                                            item_a.attr("href", response.data.url);
                                            if (response.data.name != type_id) {
                                                item.remove();
                                                $(".website-row[id='" + response.data.type_id + "']").append(item);
                                            }
                                        }
                                    },
                                    error:function (jqXHR, textStatus, errorThrown) {
                                        Materialize.toast("未知错误", 3000);
                                    }
                                });
                            });
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
                                        $("#add_site_mult").attr("href", "site_add.php?id=" + type_id);
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
                $.ajax({
                    url:"request/site_add_one.php",
                    type:"post",
                    data:$("form.add-site").serialize(),
                    async:true,
                    dataType:'json',
                    success: function (response) {
                        Materialize.toast(response.msg, 3000);
                        if (response.status == 0){
                            $(".website-row[id='" + response.data.type_id + "']").append(
                                "<div class='website-div col s3' style='margin-top: 20px; display: block;' data-id='" + response.data.id + "'>" +
                                "<a href='" + response.data.url + "' target='_blank'>" +
                                "<div class='website hoverable' style='position:relative;'>" +
                                "<img src='http://favicon.byi.pw/?url=" + response.data.url + "' width='16px' style='position: absolute;top: 50%;transform: translateY(-50%);'>" +
                                "<p class='teal-text center'> " + response.data.name + "</p>" +
                                "</div>" +
                                "</a>" +
                                "</div>"
                            );
                        }
                    },
                    error:function (jqXHR, textStatus, errorThrown) {
                        Materialize.toast("未知错误", 3000);
                    },
                    complete:function (jqXHR, textStatus, errorThrown) {
                        var modal_content = $('#modal-add-site').children('.modal-content');
                        modal_content.children('div').eq(0).children('input').val("");
                        modal_content.children('div').eq(2).children('input').val("");
                    }
                });
            });
            $.contextMenu({
                selector: 'body',
                build: function() {
                    if (body.attr("data-id") != -1){
                        return {
                            items: {
                                "add": {
                                    name: "添加背景图",
                                    callback: function() {
                                        $('#modal-add-bg').modal('open');
                                    }
                                },
                                "delete": {
                                    name: "这张背景看腻了，朕要将其打入冷宫",
                                    callback: function() {
                                        var id =$("body").attr("data-id");
                                        $.ajax({
                                            url: 'request/bg_delete.php',
                                            type: 'post',
                                            data: ("id=" + id),
                                            dataType:'json',
                                            success: function (response) {
                                                Materialize.toast(response.msg, 3000);
                                                if (response.status == 0){
                                                    var body = $("body");
                                                    if (response.data != null){
                                                        body.css("background-image","url(bg/" + response.data.url +")");
                                                        body.attr("data-id", response.data.id);
                                                    }
                                                    else {
                                                        body.css("background-image","url(https://api.ikmoe.com/moeu-api.php)");
                                                        body.attr("data-id", -1);
                                                    }
                                                }
                                            },
                                            error:function (jqXHR, textStatus, errorThrown) {
                                                Materialize.toast("未知错误", 3000);
                                            }
                                        });
                                    }
                                }
                            }
                        };
                    }
                    else {
                        return {
                            items: {
                                "add": {
                                    name: "什么辣鸡图，劳资要自己设壁纸",
                                    callback: function() {
                                        $('#modal-add-bg').modal('open');
                                    }
                                }
                            }
                        };
                    }
                }
            });
            $("a.add-bg").click(function(){
                $.ajax({
                    url: 'request/bg_add.php',
                    type: 'POST',
                    cache: false,
                    data: new FormData($('#add-bg')[0]),
                    processData: false,
                    contentType: false,
                    dataType:'json',
                    success: function (response) {
                        Materialize.toast(response.msg, 3000);
                        if (response.status == 0){
                            var body = $("body");
                            body.css("background-image","url(bg/" + response.data.url +")");
                            body.attr("data-id", response.data.id);
                        }
                    },
                    error:function (jqXHR, textStatus, errorThrown) {
                        Materialize.toast("未知错误", 3000);
                    },
                    complete:function () {
                        $("#file").val("");
                        $(".file-path").val("");
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
<body data-id="<?php if ($row['id'] != null) echo $row['id']; else echo "-1";?>" style="background-size:cover;background-image: url(<?php if ($row['url'] != null) echo "bg/" . $row['url']; else echo "https://api.ikmoe.com/moeu-api.php";?>);">
<form class="add-search">
    <div id="modal-add-search" class="modal">
        <div class="modal-content">
            <h4>添加搜索引擎</h4>
            <div class="input-field">
                <input name="name" id="name" type="text" class="validate" autocomplete="off">
                <label for="name">名称&nbsp;例如：百度</label>
            </div>
            <div class="input-field">
                <input name="url" id="url" type="text" class="validate" autocomplete="off">
                <label for="url">链接地址&nbsp;例如：baidu.com/s?wd=</label>
            </div>
        </div>
        <div class="modal-footer">
            <a href="search_add.php" class="modal-action modal-close waves-effect waves-red btn-flat">批量添加</a>
            <a class="modal-action modal-close waves-effect waves-red btn-flat ">取消</a>
            <a class="add-search modal-action modal-close waves-effect waves-green btn-flat">确定</a>
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
        <div class="modal-footer">
            <a class="modal-action modal-close waves-effect waves-red btn-flat ">取消</a>
            <a class="update-search modal-action modal-close waves-effect waves-green btn-flat">提交</a>
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
        <div class="modal-footer">
            <a class="modal-action modal-close waves-effect waves-red btn-flat ">取消</a>
            <a class="add-site-type modal-action modal-close waves-effect waves-green btn-flat">确定</a>
        </div>
    </div>
</form>
<form class="update-site-type">
    <div id="modal-update-site-type" class="modal">
        <div class="modal-content">
            <h4>修改搜索引擎</h4>
            <input type="hidden" id="id" name="id" value="" autocomplete="off"/>
            <div class="input-field">
                <input name="name" id="name" type="text" class="validate" value="" autocomplete="off">
                <label for="name">名称</label>
            </div>
        </div>
        <div class="modal-footer">
            <a class="modal-action modal-close waves-effect waves-red btn-flat ">取消</a>
            <a class="update-site-type modal-action modal-close waves-effect waves-green btn-flat">提交</a>
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
        <div class="modal-footer">
            <a href="site_add.php" id="add_site_mult" class="modal-action modal-close waves-effect waves-red btn-flat">批量添加</a>
            <a class="modal-action modal-close waves-effect waves-red btn-flat ">取消</a>
            <a class="add-site modal-action modal-close waves-effect waves-green btn-flat">确定</a>
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
        <div class="modal-footer">
            <a class="modal-action modal-close waves-effect waves-red btn-flat ">取消</a>
            <a class="update-site modal-action modal-close waves-effect waves-green btn-flat">提交</a>
        </div>
    </div>
</form>
<form id="add-bg" enctype="multipart/form-data">
    <div id="modal-add-bg" class="modal">
        <div class="modal-content">
            <h4>添加背景图片</h4>
            <div class="file-field input-field">
                <div class="btn">
                    <span>文件</span>
                    <input type="file" name="file" id="file">
                </div>
                <div class="file-path-wrapper">
                    <input id="#file-path" class="file-path validate" type="text">
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <a class="modal-action modal-close waves-effect waves-red btn-flat ">取消</a>
            <a name="submit" class="add-bg modal-action modal-close waves-effect waves-green btn-flat ">确定</a>
        </div>
    </div>
</form>
<div id="search-bar">
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
                        . "<label class=\"grey-text text-darken-3\" for=\"radio" . $row['id'] . "\">" . $row['name'] . "</label>"
                        . "</div>";
                    $checked = false;
                }
                else echo "<div class=\"col s2 radios-div\"  data-id=\"" . $row['id'] . "\">"
                            . "<input class=\"with-gap\" name=\"group1\" type=\"radio\" id=\"radio" . $row['id'] . "\" value=\"" . $row['url'] . "\"/>"
                            . "<label class=\"grey-text text-darken-3\" for=\"radio" . $row['id'] . "\">" . $row['name'] . "</label>"
                            . "</div>";
           }?>
        </div>
    </div>
</div>
<div id="tab-nav">
    <nav class="nav-extended transparent">
        <div class="nav-content">
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
                        <li data-id="<?php echo $row['id']; ?>" class="tab"><a href="#<?php echo $row['id']; ?>"  class="teal-text"><?php echo $row['name']; ?></a></li>
                    <?php } ?>
                </div>
                <li class="indicator teal" style="right: 186px; left: 68px;"></li>
            </ul>
        </div>
    </nav>
    <?php
    for ($i = 0; $i < count($site_type_ids); $i++){ ?>
        <div id="<?php echo $site_type_ids[$i] ?>" class="row website-row" style="min-height:450px; height:auto!important; height:450px;">
            <?php
            $stmt=$mysqli->prepare("SELECT * from site WHERE type_id = ?");
            $stmt->bind_param('i', $site_type_ids[$i]);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {?>
                <div class="website-div col s3" style="margin-top: 20px; display: block;" data-id="<?php echo $row['id']; ?>">
                    <a href="<?php echo $row['url'] ?>" target="_blank">
                        <div class="website hoverable" style="position:relative;">
                            <img src="http://favicon.byi.pw/?url=<?php echo $row['url'] ?>" width="16px" style="position: absolute;top: 50%;transform: translateY(-50%);">
                            <p class="teal-text center"><?php echo $row['name'] ?></p>
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