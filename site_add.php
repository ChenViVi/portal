<?php
    require_once("func.php");
    $mysqli=new mysqli($DB_HOST,$DB_USER,$DB_PASS,$DB_NAME,$DB_PORT);
    $mysqli->set_charset("utf8");
    $type_id = $_GET["id"];
    if (is_empty($type_id)) exit();
?>
<!DOCTYPE html>
<html lang="zh-cmn-Hans">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>添加网站</title>
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/materialize.min.js"></script>
    <link href="css/materialize.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <?php
    $stmt=$mysqli->prepare("SELECT * FROM site_type ORDER BY id");
    $stmt->execute();
    $result = $stmt->get_result();
    $type_ids = array();
    $type_names = array();
    while ($row = $result->fetch_assoc()){
        array_push($type_ids, $row['id']);
        array_push($type_names, $row['name']);
    }
    ?>
    <script type="text/javascript">
        $(document).ready(function() {
            $('select').material_select();
            var rowCount = 1;
            $(".add_row").click(function() {
                rowCount++;
                $(".forms").append(
                    '<div class="card col s4">' +
                    '<div class="card-content teal-text">' +
                    '<div class="row">' +
                    '<div class="input-field col s6">' +
                    '<input id="name_' + rowCount + '" name="name_' + rowCount + '" type="text" class="validate">' +
                    '<label for="name_' + rowCount + '">名称&nbsp;例如：百度</label>' +
                    '</div>' +
                    '<div class="input-field col s6">' +
                    '<select id="type_id_' + rowCount + '" name="type_id_' + rowCount + '">' +
                    '<?php
                        for($i = 0; $i < count($type_ids); $i++){
                            if ($type_ids[$i] == $type_id) echo "<option selected value=\"" . $type_ids[$i] . "\">" . $type_names[$i] . "</option>";
                            else echo "<option value=\"" . $type_ids[$i] . "\">" . $type_names[$i]  . "</option>";
                        }?>' +
                    ' </select>' +
                    '<label for="type_id_' + rowCount + '">网站类别</label>' +
                    '</div>' +
                    '<div class="input-field col s12">' +
                    '<input id="url_' + rowCount + '" name="url_' + rowCount + '" type="text" class="validate">' +
                    '<label for="url_' + rowCount + '">链接地址&nbsp;例如：www.baidu.com</label>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '<a href="#" class="teal-text close" style="position:absolute;right:1px;top:1px;z-index:999;"><i class="material-icons">close</i></a>' +
                    '</div>'
                );
                $("input[name='count']").val(rowCount);
                $('select').material_select();
                $(".close").click(function(){
                    $(this).parent().remove();
                });
                $(document).scrollTop($(document).height()-$(window).height());
            });
            $("a.add").click(function(){
                $.ajax({
                    url:"request/site_add_mult.php",
                    type:"post",
                    data:$("form.add").serialize(),
                    async:false
                });
                window.location.href='index.php';
            });
        });
    </script>
</head>
<body>
    <nav class="top-nav teal">
        <div class="container">
            <div class="nav-wrapper"><a class="brand-logo">添加网站</a></div>
        </div>
    </nav>
    <div class="container" style="padding-bottom: 20px">
        <form name="form" class="add">
            <input type="hidden" name="count" value="1"/>
            <div class="forms row">
                <div class="card col s4">
                    <div class="card-content">
                        <div class="row">
                            <div class="input-field col s6">
                                <input id="name_1" name="name_1" type="text" class="validate">
                                <label for="name_1">名称&nbsp;例如：百度</label>
                            </div>
                            <div class="input-field col s6">
                                <select id="type_id_1" name="type_id_1">
                                    <?php
                                    for($i = 0; $i < count($type_ids); $i++){
                                        if ($type_ids[$i] == $type_id) echo "<option selected value=\"" . $type_ids[$i] . "\">" . $type_names[$i] . "</option>";
                                        else echo "<option value=\"" . $type_ids[$i] . "\">" . $type_names[$i]  . "</option>";
                                    } ?>
                                </select>
                                <label for="type_id_1">网站类别</label>
                            </div>
                            <div class="input-field col s12">
                                <input id="url_1" name="url_1" type="text" class="validate">
                                <label for="url_1">链接地址&nbsp;例如：baidu.com/s?wd=</label>
                            </div>
                        </div>
                    </div>
                    <a href="#" class="teal-text close" style="position:absolute;right:1px;top:1px;z-index:999;"><i class="material-icons">close</i></a>
                </div>
            </div>
            <a type="button" class="btn waves-light blue add_row ">添加一项</a>
            <a class="add btn waves-light teal">提交</a>
        </form>
    </div>
</body>
</html>
