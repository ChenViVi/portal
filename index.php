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
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.9/css/all.css" integrity="sha384-5SOiIsAziJl6AWe0HWRKTXlfcSHKmYV4RBF18PPJ173Kzn7jzMyFuTtk8JA7QQG1" crossorigin="anonymous">
    <style>
        .searchbar{
            margin:20px auto;
            background-color: rgba(255,255,255,0.65);
            width:60%;
            border-radius:3px;
        }
        .tabnav{
            min-height:480px; height:auto!important; height:480px;
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
            $('.searchbar').on('keydown',function(event){
                if(event.keyCode == 13){
                    window.open ($('input[name=group1]:checked').val() + $("input[name='search_param']").val());
                    $("input[name='search_param']").val("");
                }
            });
            $(".searchbtn").click(function(){
                window.open ($('input[name=group1]:checked').val() + $("input[name='search_param']").val());
                $("input[name='search_param']").val("");
            });
            $('#search_radios').sortable({
                start: function(event, ui) {
                    var start_pos = ui.item.index();
                    ui.item.data('start_pos', start_pos);
                },
                update: function (event, ui) {
                    ui.item.data('end_pos', ui.item.index());
                    var start_pos = ui.item.data('start_pos');
                    var end_pos = ui.item.index();
                    var start,end,search_radios = $("#search_radios");
                    if (start_pos < end_pos){
                        start = search_radios.children().eq(end_pos).attr("title");
                        end = search_radios.children().eq(end_pos-1).attr("title");
                    }
                    else {
                        start = search_radios.children().eq(end_pos).attr("title");
                        end = search_radios.children().eq(end_pos+1).attr("title");
                    }
                    if (start != end){
                        alert("start=" + start + " end=" + end);
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
<body style="background-size:cover;background-image: url(<?php if ($background !=null) echo "bg/" . $row['url']; else echo "bg/bg_default.jpg";?>);">
<ul id="slide-out" class="side-nav" style="transform: translateX(-100%);">
    <li>
        <div class="userView" style="height: 140px">
            <div class="background">
                <img src="images/header.jpg" >
            </div>
        </div>
    </li>
    <li class="bold"><a class="waves-effect" href="index.php"><i class="material-icons">search</i>搜索引擎</a></li>
    <li class="bold"><a class="waves-effect" href="index.php"><i class="material-icons">language</i>站点分类</a></li>
    <li class="bold"><a class="waves-effect" href="#"><i class="material-icons">perm_media</i>背景</a></li>
</ul>
<a href="#" class="button-collapse" data-activates="slide-out"><i class="material-icons small">menu</i></a>
<div class= "searchbar">
    <div class="row">
        <div class="row col s12" style="position:relative;">
            <div class="input-field col s11" >
                <input name="search_param" type="text" class="validate" autocomplete="off">
            </div>
            <a class="waves-effect waves-light btn col s1 searchbtn" style="position: absolute;top: 50%;transform: translateY(-50%);"><i class="material-icons">search</i></a>
        </div>
        <div class="row col s12" id="search_radios">
            <?php
            $stmt=$mysqli->prepare("SELECT * FROM search ORDER BY id");
            $stmt->execute();
            $result = $stmt->get_result();
            $checked = true;
            while ($row = $result->fetch_assoc()) {
                if($checked){
                    echo "<div class=\"col s2\"  title=\"" . $row['id'] . "\">"
                        . "<input checked class=\"with-gap\" name=\"group1\" type=\"radio\" id=\"radio" . $row['id'] . "\" value=\"" . $row['url'] . "\"/>"
                        . "<label class=\"grey-text text-darken-3\" for=\"radio" . $row['id'] . "\">" . $row['name'] . "</label>"
                        . "</div>";
                    $checked = false;
                }
                else echo "<div class=\"col s2\"  title=\"" . $row['id'] . "\">"
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
                $stmt=$mysqli->prepare("SELECT * FROM site_type");
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
<!--<script type="text/javascript">
    $(".button-collapse").sideNav();
    $('#search_radios').sortable({
        start: function(event, ui) {
            var start_pos = ui.item.index();
            ui.item.data('start_pos', start_pos);
        },
        update: function (event, ui) {
            ui.item.data('end_pos', ui.item.index());
            var start_pos = ui.item.data('start_pos');
            var end_pos = ui.item.index();
            var start,end,search_radios = $("#search_radios");
            if (start_pos < end_pos){
                start = search_radios.children().eq(end_pos).attr("title");
                end = search_radios.children().eq(end_pos-1).attr("title");
            }
            else {
                start = search_radios.children().eq(end_pos).attr("title");
                end = search_radios.children().eq(end_pos+1).attr("title");
            }
            if (start != end){
                $.ajax({
                    url:"admin/search_sort.php",
                    type:"get",
                    data:("start=" + start + "&end=" + end),
                    async:false
                });
                Materialize.toast("排序成功", 2000);
            }
        }
    });
    $('.website_row').sortable();
</script>-->
</body>
</html>