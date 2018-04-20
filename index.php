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
    <title>ViVi的传送门</title>
    <link href="css/ghpages-materialize.css" type="text/css" rel="stylesheet" media="screen,projection">
    <link href="css/materializecss-font.css" rel="stylesheet" type="text/css">
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/jquery-ui.js"></script>
    <script src="js/materialize.js"></script>
    <script src="js/jquery.contextMenu.js" type="text/javascript"></script>
    <link href="js/jquery.contextMenu.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        .searchbar{
            margin:20px auto;
            background-color: rgba(255,255,255,0.65);
            width:60%;
            border-radius:3px;
        }
        .tabnav{
            min-height:500px; height:auto!important; height:500px;
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
            var search_param = $("input[name='search-param']");
            $('.searchbar').on('keydown',function(event){
                if(event.keyCode == 13){
                    window.open ($('input[name=group1]:checked').val() + search_param.val());
                    search_param.val("");
                }
            });
            $(".searchbtn").click(function(){
                window.open ($('input[name=group1]:checked').val() + search_param.val());
                search_param.val("");
            });
            var search_radios = $('#search-radios');
            search_radios.sortable({
                start: function(event, ui) {
                    var start_pos = ui.item.index();
                    ui.item.data('start_pos', start_pos);
                },
                update: function (event, ui) {
                    ui.item.data('end_pos', ui.item.index());
                    var start_pos = ui.item.data('start_pos');
                    var end_pos = ui.item.index();
                    var start,end,search_radios = $("#search-radios");
                    if (start_pos < end_pos){
                        start = search_radios.children().eq(end_pos).attr("tabindex");
                        end = search_radios.children().eq(end_pos-1).attr("tabindex");
                    }
                    else {
                        start = search_radios.children().eq(end_pos).attr("tabindex");
                        end = search_radios.children().eq(end_pos+1).attr("tabindex");
                    }
                    if (start != end){
                        $.ajax({
                            url:"admin/search_sort.php",
                            type:"get",
                            data:("start=" + start + "&end=" + end),
                            async:true,
                            dataType:'json',
                            success: function (response) {
                                if (response.status == 0){
                                    Materialize.toast("排序成功", 2000);
                                }
                                else {
                                    Materialize.toast(response.msg, 3000);
                                }
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
                    "update": {name: "编辑",callback: function() {
                        var id = $(this).attr("tabindex");
                        var name = $(this).children('label').text();
                        var url = $(this).children('input').val();
                        var modal = $('#modal-update-search');
                        $("a.update-search").click(function(){
                            $.ajax({
                                url:"admin/search_modify.php",
                                type:"get",
                                data:$("form.update-search").serialize(),
                                async:false
                            });
                            window.location.href='index.php';
                            Materialize.toast("修改成功", 3000);
                        });
                        var modal_content = modal.children('.modal-content');
                        modal_content.children('input').val(id);
                        modal_content.children('div').eq(0).children('input').val(name);
                        modal_content.children('div').eq(1).children('input').val(url);
                        modal.modal('open');
                    }},
                    "delete": {name: "删除",callback: function() {
                        var id = $(this).attr("tabindex");
                        $.ajax({
                            url:"admin/search_modify.php",
                            type:"get",
                            data:("id=" + id),
                            async:false
                        });
                        window.location.href='index.php';
                        Materialize.toast("删除成功", 3000);
                    }},
                    "add": {name: "添加",callback: function() {
                        $('#modal-add-search').modal('open');
                    }},
                    "sep1": "---------",
                    "quit": {name: "控制台",callback: function() {
                        window.location.href='admin/index.php';
                    }}
                }
            });
            $("a.add-search").click(function(){
                $.ajax({
                    url:"admin/search_modify.php",
                    type:"get",
                    data:$("form.add-search").serialize(),
                    async:false
                });
                window.location.href='index.php';
                Materialize.toast("添加成功", 3000);
            });
        });
    </script>
</head>
<?php
$stmt=$mysqli->prepare("SELECT * FROM bg ORDER BY rand() limit 1");
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$background = $row['url'];
?>
<body style="background-size:cover;background-image: url(<?php if ($background !=null) echo "bg/" . $row['url']; else echo "bg/bg_default.jpg";?>);"
<form class="add-search">
    <div id="modal-add-search" class="modal">
        <input type="hidden" name="count" value="1"/>
        <div class="modal-content">
            <h4>添加搜索引擎</h4>
            <div class="input-field">
                <input name="name_1" id="name_1" type="text" class="validate" autocomplete="off">
                <label for="name_1">名称&nbsp;例如：百度</label>
            </div>
            <div class="input-field">
                <input name="url_1" id="url_1" type="text" class="validate" autocomplete="off">
                <label for="url_1">链接地址&nbsp;例如：baidu.com/s?wd=</label>
            </div>
        </div>
        <div class="modal-footer">
            <a href="admin/search_add.php" class="modal-action modal-close waves-effect waves-red btn-flat ">批量添加</a>
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
                <input name="name_1" id="name_1" type="text" class="validate" value="" autocomplete="off">
                <label for="name_1">名称</label>
            </div>
            <div class="input-field">
                <input name="url_1" id="url_1" type="text" class="validate" value="" autocomplete="off">
                <label for="url_1">链接地址</label>
            </div>
        </div>
        <div class="modal-footer">
            <a class="modal-action modal-close waves-effect waves-red btn-flat ">取消</a>
            <a class="update-search modal-action modal-close waves-effect waves-green btn-flat">提交</a>
        </div>
    </div>
</form>
<div class= "searchbar">
    <div class="row">
        <div class="row col s12" style="position:relative;">
            <div class="input-field col s11" >
                <input name="search-param" type="text" class="validate" autocomplete="off">
            </div>
            <a class="waves-effect waves-light btn col s1 searchbtn" style="position: absolute;top: 50%;transform: translateY(-50%);"><i class="material-icons">search</i></a>
        </div>
        <div class="row col s12" id="search-radios">
            <?php
            $stmt=$mysqli->prepare("SELECT * FROM search ORDER BY id");
            $stmt->execute();
            $result = $stmt->get_result();
            $checked = true;
            while ($row = $result->fetch_assoc()) {
                if($checked){
                    echo "<div class=\"col s2 radios-div\"  tabindex=\"" . $row['id'] . "\">"
                        . "<input checked class=\"with-gap\" name=\"group1\" type=\"radio\" id=\"radio" . $row['id'] . "\" value=\"" . $row['url'] . "\"/>"
                        . "<label class=\"grey-text text-darken-3\" for=\"radio" . $row['id'] . "\">" . $row['name'] . "</label>"
                        . "</div>";
                    $checked = false;
                }
                else echo "<div class=\"col s2 radios-div\"  tabindex=\"" . $row['id'] . "\">"
                            . "<input class=\"with-gap\" name=\"group1\" type=\"radio\" id=\"radio" . $row['id'] . "\" value=\"" . $row['url'] . "\"/>"
                            . "<label class=\"grey-text text-darken-3\" for=\"radio" . $row['id'] . "\">" . $row['name'] . "</label>"
                            . "</div>";
           }?>
        </div>
    </div>
</div>
<div class="tabnav">
    <nav class="nav-extended transparent">
        <div class="nav-content">
            <ul class="tabs transparent">
                <?php
                $stmt=$mysqli->prepare("SELECT * FROM site_type ORDER BY id");
                $stmt->execute();
                $result = $stmt->get_result();
                while ($row = $result->fetch_assoc()) { ?>
                    <li class="tab"><a href="#<?php echo $row['id']; ?>"  class="teal-text"><?php echo $row['name']; ?></a></li>
                <?php } ?>
                <li class="indicator teal" style="right: 186px; left: 68px;"></li>
            </ul>
        </div>
    </nav>
    <?php
    $stmt=$mysqli->prepare("SELECT id FROM site_type ORDER BY id");
    $stmt->execute();
    $type_result = $stmt->get_result();
    while($type_row = $type_result->fetch_assoc()){ ?>
    <div id="<?php echo $type_row['id'] ?>" class="row website_row">
        <?php
        $stmt=$mysqli->prepare("SELECT * from site WHERE type_id = ?");
        $stmt->bind_param('i', $type_row['id']);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {?>
            <div class="col s3" style="margin-top: 20px; display: block;">
                <a href="<?php echo $row['url'] ?>" target="_blank">
                    <div class="website hoverable" style="position:relative;">
                        <img src="http://favicon.byi.pw/?url=<?php echo $row['url'] ?>" width="16px" style="position: absolute;top: 50%;transform: translateY(-50%);">
                        <p class="teal-text center"><?php echo $row['name'] ?></p>
                    </div>
                </a>
            </div>
        <?php } ?>
    </div>
    <?php } mysqli_close($mysqli);?>
</div>
</body>
</html>