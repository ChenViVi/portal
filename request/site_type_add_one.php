<?php
require_once("../func.php");
$status = 1;
$msg = "出现未知错误";
$data = "";
$name = $_POST["name"];
$mysqli=new mysqli($DB_HOST,$DB_USER,$DB_PASS,$DB_NAME,$DB_PORT);
$mysqli->set_charset("utf8");
if ($mysqli->connect_errno){
    $msg = "数据库连接失败，请检查 config.php 配置文件";
}
else{
    if (is_empty($name)){
        $msg = "参数错误";
    }
    else{
        $stmt=$mysqli->prepare("SELECT * FROM site_type WHERE name = ?");
        $stmt->bind_param('s', $name);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()){
            $msg = "两个相同名称的分类是要做什么用呢？";
        }
        else {
            $stmt=$mysqli->prepare("INSERT INTO site_type (name) VALUES (?)");
            $stmt->bind_param('s', $name);
            $stmt->execute();
            $stmt=$mysqli->prepare("SELECT * FROM site_type WHERE name = ?");
            $stmt->bind_param('s', $name);
            $stmt->execute();
            $result = $stmt->get_result();
            $status = 0;
            $msg = "添加成功";
            $data = $result->fetch_assoc();
        }
    }
    mysqli_close($mysqli);
}
echo json_encode(array(
    'status' => $status,
    'msg' => $msg,
    'data' => $data,
));