<?php
require_once("../func.php");
$status = 1;
$msg = "出现未知错误，去问问神奇的海螺吧";
$data = "";
$type_id = $_POST["type_id"];
$name = $_POST["name"];
$url = $_POST["url"];
$mysqli=new mysqli($DB_HOST,$DB_USER,$DB_PASS,$DB_NAME,$DB_PORT);
$mysqli->set_charset("utf8");
if ($mysqli->connect_errno){
    $msg = "数据库连接失败，请检查 config.php 配置文件";
}
else if (is_empty($type_id) || is_empty($url) || is_empty($name)){
    $msg = "你是不是漏了什么？";
}
else if (utf8_length($name) > 20) {
    $msg = "插入这么长的东西的话，数据库酱会痛得受不了的哟~";
}
else if (!is_url($url)) {
    $msg = "这个链接好像不是很对呢，是不是开头没有加 'http://' 或者 'https://' 呢？";
}
else{
    $stmt=$mysqli->prepare("SELECT * FROM site_type WHERE id = ?");
    $stmt->bind_param('i', $type_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if (!($row = $result->fetch_assoc())) {
        $msg = "没有这个网站分类哇~";
    }
    else {
        $stmt=$mysqli->prepare("SELECT * FROM site WHERE type_id=? AND url = ?");
        $stmt->bind_param('is', $type_id, $url);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $msg = "此搜索引已经添加过了呢~名称是：" . $row['name'];
        }
        else {
            $stmt=$mysqli->prepare("INSERT INTO site (type_id, name, url) VALUES (?,?,?)");
            $stmt->bind_param('iss', $type_id, $name, $url);
            $stmt->execute();
            $stmt=$mysqli->prepare("SELECT * FROM site WHERE type_id=? AND url = ?");
            $stmt->bind_param('is', $type_id, $url);
            $stmt->execute();
            $result = $stmt->get_result();
            $status = 0;
            $msg = "添加成功";
            $data = $result->fetch_assoc();
        }
    }
}
mysqli_close($mysqli);
echo json_encode(array(
    'status' => $status,
    'msg' => $msg,
    'data' => $data,
));