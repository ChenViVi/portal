<?php
require_once("../func.php");
$mysqli=new mysqli($DB_HOST,$DB_USER,$DB_PASS,$DB_NAME,$DB_PORT);
$mysqli->set_charset("utf8");
?>﻿
<!DOCTYPE html>
<html lang="zh-cmn-Hans">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="icon" href="../images/favicon.ico" type="image/x-icon" />
    <link rel="shortcut icon" href="../images/favicon.ico" type="image/x-icon" />
    <title>背景</title>
    <link href="../css/ghpages-materialize.css" type="text/css" rel="stylesheet" media="screen,projection">
    <link href="../css/materializecss-font.css" rel="stylesheet" type="text/css">
    <script src="../js/jquery-3.3.1.min.js"></script>
    <script src="../js/jquery-ui.js"></script>
    <script src="../js/materialize.js"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script type="text/javascript">
      $(document).ready(function() {
        $('.modal').modal();
        $('.site_type').sortable({
          start: function(event, ui) {
            var start_pos = ui.item.index();
            ui.item.data('start_pos', start_pos);
          },
          update: function (event, ui) {
            ui.item.data('end_pos', ui.item.index());
            var start_pos = ui.item.data('start_pos');
            var end_pos = ui.item.index();
            var start,end,tbody = $(".site_type");
            if (start_pos < end_pos){
              start = tbody.children("li").eq(end_pos).children("a").eq(0).attr("name");
              end = tbody.children("li").eq(end_pos-1).children("a").eq(0).attr("name");
            }
            else {
              start = tbody.children("li").eq(end_pos).children("a").eq(0).attr("name");
              end = tbody.children("li").eq(end_pos+1).children("a").eq(0).attr("name");
            }
            if (start != end){
              $.ajax({
                url:"site_type_sort.php",
                type:"get",
                data:("start=" + start + "&end=" + end),
                async:false
              });
              Materialize.toast("排序成功", 2000);
            }
          }
        });
        $("a.add_site_type").click(function(){
          $.ajax({
            url:"site_type_modify.php",
            type:"get",
            data:$("form.add_site_type").serialize(),
            async:false
          });
          window.location.href='index.php';
          Materialize.toast("添加成功", 2000);
        });
        $("a.add").click(function(){
          $.ajax({
            url: 'upload_bg.php',
            type: 'POST',
            cache: false,
            data: new FormData($('#upload_bg')[0]),
            processData: false,
            contentType: false,
            dataType:'json',
            success: function (response) {
              Materialize.toast(response.msg, 3000);
              if (response.status == 0){
                $("#content").append(
                    '<div class="col s3" id="item_'+ response.data.id + '">' +
                    '<div class="card">' +
                    '<div class="card-image">' +
                    '<img src="../bg/' + response.data.url + '" height="180px">' +
                    '</div>' +
                    '<div class="card-action">' +
                    '<a class="delete red-text text-lighten-3" href="#" name="'+ response.data.id + '">删除</a>' +
                    '</div>' +
                    '</div>' +
                    '</div>'
                );
                delete_click();
                $("#file").val("");
                $(".file-path").val("");
              }
            },
            error:function (jqXHR, textStatus, errorThrown) {
              Materialize.toast("未知错误", 3000);
              $("#file").val("");
              $(".file-path").val("");
              /*alert(jqXHR.responseText);
               alert(jqXHR.status);
               alert(jqXHR.readyState);
               alert(jqXHR.statusText);
               alert(textStatus);
               alert(errorThrown);*/
            }
          });
        });
        delete_click();
        function delete_click() {
          $("a.delete").click(function(){
            var id =$(this).attr("name");
            $.ajax({
              url: 'bg_modify.php',
              type: 'get',
              data: ("id=" + id),
              dataType:'json',
              success: function (response) {
                Materialize.toast(response.msg, 3000);
                if (response.status == 0){
                  $("#item_" + id).remove();
                }
              },
              error:function (jqXHR, textStatus, errorThrown) {
                Materialize.toast("未知错误", 3000);
              }
            });
          });
        }
      });
    </script>
  </head>
  <body>
    <header>
      <ul id="nav-mobile" class="side-nav fixed" style="transform: translateX(0%);">
        <li>
          <div class="userView" style="height: 140px">
            <div class="background">
              <img src="../images/header.jpg" >
            </div>
          </div>
        </li>
        <li class="bold"><a class="waves-effect" href="index.php"><i class="material-icons">search</i>搜索引擎</a></li>
        <ul class="collapsible collapsible-accordion">
          <li class="bold"><a class="site_type collapsible-header waves-effect waves-teal"><i class="material-icons">language</i>站点分类<i class="material-icons right">arrow_drop_down</i></a>
            <div class="collapsible-body">
              <ul>
                <?php
                $stmt=$mysqli->prepare("SELECT * FROM site_type ORDER BY id");
                $stmt->execute();
                $result = $stmt->get_result();
                ?>
                <div class="site_type">
                  <?php while ($row = $result->fetch_assoc()) {?>
                    <li><a class="waves-effect" href="<?php echo "site_type.php?id=" . $row['id']; ?>" name="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></a></li>
                  <?php } ?>
                </div>
                <li><div class="divider"></div></li>
                <li class="center"><button data-target="modal_add_type" class="btn blue btn waves-effect waves-blue">添加分类</button></li>
              </ul>
            </div>
          </li>
        </ul>
        <li class="bold"><a class="waves-effect" href="#!"><i class="material-icons">group_work</i>悬浮按钮</a></li>
        <li class="bold active teal"><a class="waves-effect" href="#"><i class="material-icons">perm_media</i>背景</a></li>
        <li><div class="divider"></div></li>
        <li><a href="../index.php" class="btn waves-effect waves-teal">查看效果</a></li>
      </ul>
    </header>
    <main>
      <form class="add_site_type">
        <div id="modal_add_type" class="modal">
          <div class="modal-content">
            <h4>添加网站分类</h4>
            <div class="input-field">
              <input name="name" id="name" type="text" class="validate">
              <label for="name">名称</label>
            </div>
          </div>
          <div class="modal-footer">
            <a class="modal-action modal-close waves-effect waves-red btn-flat ">取消</a>
            <a class="add_site_type modal-action modal-close waves-effect waves-green btn-flat ">确定</a>
          </div>
        </div>
      </form>
      <nav class="top-nav teal">
        <div class="container">
          <div class="nav-wrapper"><a class="page-title">背景</a></div>
        </div>
      </nav>
      <div class="container">
        <button data-target="modal_add" type="button" class="btn blue btn waves-effect waves-blue" style="margin-top: 20px">添加</button>
        <form id="upload_bg" class="add" method="post" action="upload_bg.php" enctype="multipart/form-data">
          <div id="modal_add" class="modal">
            <input type="hidden" name="count" value="1"/>
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
              <a name="submit" class="add modal-action modal-close waves-effect waves-green btn-flat ">确定</a>
            </div>
          </div>
        </form>
        <div id="content" class="row" style="margin-top: 20px; display: block;">
          <?php
          $stmt=$mysqli->prepare("SELECT * FROM bg ORDER BY id");
          $stmt->execute();
          $result = $stmt->get_result();
          while ($row = $result->fetch_assoc()) { ?>
            <div class="col s3" id="item_<?php echo $row['id']?>">
              <div class="card">
                <div class="card-image">
                  <img src="../bg/<?php echo $row['url']?>" height="180px">
                </div>
                <div class="card-action">
                  <a class="delete red-text text-lighten-3" href="#" name="<?php echo $row['id']?>">删除</a>
                </div>
              </div>
            </div>
          <?php } ?>
        </div>
      </div>
    </main>
  </body>
</html>
<?php mysqli_close($mysqli);?>﻿
