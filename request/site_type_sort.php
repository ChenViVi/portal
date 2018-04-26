<?php
require_once("../func.php");
$status = 1;
$msg = "出现未知错误";
$data = "";
$pre = $_POST["start"];
$now = $_POST["end"];
$mysqli=new mysqli($DB_HOST,$DB_USER,$DB_PASS,$DB_NAME,$DB_PORT);
$mysqli->set_charset("utf8");
if ($mysqli->connect_errno){
    $msg = "数据库连接失败，请检查 config.php 配置文件";
}
else {
    if (!is_empty($pre) && !is_empty($now)  && $pre!=$now){
        $stmt=$mysqli->prepare("SELECT * FROM site WHERE type_id = ?");
        $stmt->bind_param('i', $now);
        $stmt->execute();
        $result = $stmt->get_result();
        $now_sites = array();
        while ($row = $result->fetch_assoc()){
            array_push($now_sites, $row['id']);
        }
        $stmt=$mysqli->prepare("UPDATE site SET type_id = ? WHERE type_id = ?");
        $stmt->bind_param('ii', $now, $pre);
        $stmt->execute();
        for ($i = 0 ; $i < count($now_sites); $i++){
            $stmt=$mysqli->prepare("UPDATE site SET type_id = ? WHERE id = ?");
            $stmt->bind_param('ii', $pre, $now_sites[$i]);
            $stmt->execute();
        }
        if ($pre < $now){
            $stmt=$mysqli->prepare("SELECT * FROM site_type WHERE id >= ? AND id <= ? ORDER BY id");
            $stmt->bind_param('ii', $pre, $now);
            $stmt->execute();
        }
        else {
            $stmt=$mysqli->prepare("SELECT * FROM site_type WHERE id >= ? AND id <= ? ORDER BY id DESC");
            $stmt->bind_param('ii', $now, $pre);
            $stmt->execute();
        }
        $result = $stmt->get_result();
        $ids = array();
        $names = array();
        while ($row = $result->fetch_assoc()) {
            array_push($ids,$row['id']);
            array_push($names,$row['name']);
        }
        $index_name = $names[0];
        for ($i=1;$i<count($ids);$i++){
            $stmt=$mysqli->prepare("UPDATE site_type SET name = ? WHERE id = ?");
            $stmt->bind_param('si', $names[$i], $ids[$i-1]);
            $stmt->execute();
        }
        $stmt=$mysqli->prepare("UPDATE site_type SET name = ? WHERE id = ?");
        $stmt->bind_param('si', $index_name, $now);
        $stmt->execute();
        $status = 0;
        $msg = "排序成功";
    }
    else $msg="参数错误";
}
echo json_encode(array(
    'status' => $status,
    'msg' => $msg,
    'data' => $data
));