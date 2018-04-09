<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>添加搜索引擎</title>
    <link href="css/ghpages-materialize.css" type="text/css" rel="stylesheet" media="screen,projection">
    <link href="css/materializecss-font.css" rel="stylesheet" type="text/css">
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/materialize.js"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script type="text/javascript">
        $(document).ready(function() {
            $('select').material_select();
            $('.datepicker').pickadate();
            var rowCount = 1;
            $(".add_row").click(function() {
                rowCount++;
                $(".forms").append(
                    '<div class="card col s4">' +
                    '<div class="card-content teal-text">' +
                    '<div class="row">' +
                    '<div class="input-field col s12">' +
                    '<input id="name_' + rowCount + '" name="name_' + rowCount + '" type="text" class="validate">' +
                    '<label for="name_' + rowCount + '">名称&nbsp;例如：百度</label>' +
                    '</div>' +
                    '<div class="input-field col s12">' +
                    '<input id="url_' + rowCount + '" name="url_' + rowCount + '" type="text" class="validate">' +
                    '<label for="url_' + rowCount + '">链接地址&nbsp;例如：baidu.com/s?wd=</label>' +
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
                    url:"search_modify.php",
                    type:"get",
                    data:$("form.add").serialize(),
                    async:false
                });
                window.location.href='search.php';
            });
        });
    </script>
</head>
<body>
    <nav class="top-nav teal">
        <div class="container">
            <div class="nav-wrapper"><a class="page-title">添加搜索引擎</a></div>
        </div>
    </nav>
    <div class="container" style="padding-bottom: 20px">
        <form name="form" class="add">
            <input type="hidden" name="count" value="1"/>
            <div class="forms row">
                <div class="card col s4">
                    <div class="card-content">
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="name_1" name="name_1" type="text" class="validate">
                                <label for="name_1">名称&nbsp;例如：百度</label>
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
