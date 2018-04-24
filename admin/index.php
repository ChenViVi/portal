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
    <title>搜索引擎</title>
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
        $('tbody').sortable({
          start: function(event, ui) {
            var start_pos = ui.item.index();
            ui.item.data('start_pos', start_pos);
          },
          update: function (event, ui) {
            ui.item.data('end_pos', ui.item.index());
            var start_pos = ui.item.data('start_pos');
            var end_pos = ui.item.index();
            var start,end,tbody = $("tbody");
            if (start_pos < end_pos){
              start = tbody.children("tr").eq(end_pos).children("td").eq(0).text();
              end = tbody.children("tr").eq(end_pos-1).children("td").eq(0).text();
            }
            else {
              start = tbody.children("tr").eq(end_pos).children("td").eq(0).text();
              end = tbody.children("tr").eq(end_pos+1).children("td").eq(0).text();
            }
            if (start != end){
              $.ajax({
                url:"search_sort.php",
                type:"get",
                data:("start=" + start + "&end=" + end),
                async:false
              });
              Materialize.toast("排序成功", 2000);
            }
          }
        });
        $("a.add").click(function(){
          $.ajax({
            url:"search_modify.php",
            type:"get",
            data:$("form.add").serialize(),
            async:false
          });
          window.location.href='index.php';
          Materialize.toast("添加成功", 2000);
        });
        $("a.update").click(function(){
          var id = $(this).attr("name");
          $.ajax({
            url:"search_modify.php",
            type:"get",
            data:$("form.update[name='"+id+"']").serialize(),
            async:false
          });
          window.location.href='index.php';
          Materialize.toast("修改成功", 2000);
        });
        $("a.delete").click(function(){
          var id = $(this).attr("name");
          $.ajax({
            url:"search_modify.php",
            type:"get",
            data:$("form.delete[name='"+id+"']").serialize(),
            async:false
          });
          window.location.href='index.php';
          Materialize.toast("删除成功", 2000);
        });
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
        <li class="bold"><a class="waves-effect active teal" href="#"><i class="material-icons">search</i>搜索引擎</a></li>
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
        <li class="bold"><a class="waves-effect" href="bg.php"><i class="material-icons">perm_media</i>背景</a></li>
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
          <div class="nav-wrapper"><a class="page-title">搜索引擎</a></div>
        </div>
      </nav>
      <div class="container">
        <button data-target="modal_add" type="button" class="btn blue btn waves-effect waves-blue" style="margin-top: 20px">添加</button>
        <form class="add">
          <div id="modal_add" class="modal">
            <input type="hidden" name="count" value="1"/>
            <div class="modal-content">
              <h4>添加搜索引擎</h4>
                <div class="input-field">
                  <input name="name_1" id="name_1" type="text" class="validate">
                  <label for="name_1">名称&nbsp;例如：百度</label>
                </div>
                <div class="input-field">
                  <input name="url_1" id="url_1" type="text" class="validate">
                  <label for="url_1">链接地址&nbsp;例如：baidu.com/s?wd=</label>
                </div>
            </div>
            <div class="modal-footer">
              <a href="search_add.php" class="modal-action modal-close waves-effect waves-red btn-flat ">批量添加</a>
              <a class="modal-action modal-close waves-effect waves-red btn-flat ">取消</a>
              <a class="add modal-action modal-close waves-effect waves-green btn-flat ">确定</a>
            </div>
          </div>
        </form>
        <table class="responsive-table highlight sortable">
          <thead>
            <tr>
                <th hidden>编号</th>
                <th>名称</th>
                <th>搜索链接</th>
                <th>操作</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $stmt=$mysqli->prepare("SELECT * FROM search ORDER BY id");
            $stmt->execute();
            $result = $stmt->get_result();
            ?>
            <?php while ($row = $result->fetch_assoc()) {?>
              <tr>
                <td hidden><?php echo $row['id']; ?></td>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['url']; ?></td>
                <td>
                  <a class="waves-effect waves-light btn btn-sm btn-success" data-target="modal_update_<?php echo $row['id']; ?>">修改</a>
                  <a type="submit" class="waves-effect waves-light btn red lighten-1" data-target="modal_delete_<?php echo $row['id']; ?>">删除</a>
                  <form name="<?php echo $row['id']; ?>" class="update">
                    <div id="modal_update_<?php echo $row['id']; ?>" class="modal">
                      <div class="modal-content">
                        <h4>修改搜索引擎</h4>
                        <input type="hidden" id="id" name="id" value="<?php echo $row['id']; ?>"/>
                        <div class="input-field">
                          <input name="name_1" id="name_1" type="text" class="validate" value="<?php echo $row['name']; ?>">
                          <label for="name_1">名称</label>
                        </div>
                        <div class="input-field">
                          <input name="url_1" id="url_1" type="text" class="validate" value="<?php echo $row['url']; ?>">
                          <label for="url_1">链接地址</label>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <a class="modal-action modal-close waves-effect waves-red btn-flat ">取消</a>
                        <a name="<?php echo $row['id']; ?>" class="update modal-action modal-close waves-effect waves-green btn-flat">提交</a>
                      </div>
                    </form>
                  </div>
                  <form name="<?php echo $row['id']; ?>" class="delete">
                    <div id="modal_delete_<?php echo $row['id']; ?>" class="modal">
                      <div class="modal-content">
                        <h4>确定要删除<?php echo $row['name']; ?>吗？</h4>
                        <input type="hidden" id="id" name="id" value="<?php echo $row['id']; ?>"/>
                      </div>
                      <div class="modal-footer">
                        <a class="modal-action modal-close waves-effect waves-red btn-flat ">取消</a>
                        <a name="<?php echo $row['id']; ?>" class="delete modal-action modal-close waves-effect waves-green btn-flat ">确定</a>
                      </div>
                    </form>
                  </div>
                </td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </main>
  </body>
</html>
<?php mysqli_close($mysqli);?>﻿