<?php
require_once("../func.php");
$status = 1;
$msg = "出现未知错误，去问问神奇的海螺吧";
$data = "";
$name = $_POST["name"];
$url = $_POST["url"];
$mysqli=new mysqli($DB_HOST,$DB_USER,$DB_PASS,$DB_NAME,$DB_PORT);
$mysqli->set_charset("utf8");
if ($mysqli->connect_errno){
    $msg = "数据库连接失败，请检查 config.php 配置文件";
}
else if (is_empty($name) || is_empty($url)){
    $msg = "你是不是漏了什么？";
}
else if (utf8_length($name) > 6) {
    $msg = "插入这么长的东西的话，数据库酱会痛得受不了的哟~";
}
else{
    $stmt=$mysqli->prepare("SELECT * FROM search WHERE url = ?");
    $stmt->bind_param('s', $url);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()){
        $msg = "此搜索引已经添加过了呢~名称是：" . $row['name'];
    }
    else {
        $stmt=$mysqli->prepare("INSERT INTO search (name, url) VALUES (?,?)");
        $stmt->bind_param('ss', $name, $url);
        $stmt->execute();
        $stmt=$mysqli->prepare("SELECT * FROM search WHERE url = ?");
        $stmt->bind_param('s', $url);
        $stmt->execute();
        $result = $stmt->get_result();
        $status = 0;
        $msg = "添加成功";
        $data = $result->fetch_assoc();
    }
}
mysqli_close($mysqli);
echo json_encode(array(
    'status' => $status,
    'msg' => $msg,
    'data' => $data,
));