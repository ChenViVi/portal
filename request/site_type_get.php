<?php
require_once("../func.php");
$status = 1;
$msg = "出现未知错误，去问问神奇的海螺吧";
$data = array();
$mysqli=new mysqli($DB_HOST,$DB_USER,$DB_PASS,$DB_NAME,$DB_PORT);
$mysqli->set_charset("utf8");
if ($mysqli->connect_errno){
    $msg = "数据库连接失败，请检查 config.php 配置文件";
}
else{
    $stmt=$mysqli->prepare("SELECT * FROM site_type ORDER BY id");
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        array_push($data,$row);
    }
    $status = 0;
    $msg = "查询成功";
    mysqli_close($mysqli);
    echo json_encode(array(
        'status' => $status,
        'msg' => $msg,
        'data' => $data,
    ));
}