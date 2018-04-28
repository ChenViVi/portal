<?php
require_once("../func.php");
$status = 1;
$msg = "出现未知错误，去问问神奇的海螺吧";
$pre = $_POST["start"];
$now = $_POST["end"];
$type_id = $_POST["type_id"];
$mysqli=new mysqli($DB_HOST,$DB_USER,$DB_PASS,$DB_NAME,$DB_PORT);
$mysqli->set_charset("utf8");
if ($mysqli->connect_errno){
    $msg = "数据库连接失败，请检查 config.php 配置文件";
}
else{
    if (!is_empty($pre) && !is_empty($now) && !is_empty($type_id) && $pre!=$now){
        if ($pre < $now){
            $stmt=$mysqli->prepare("SELECT * FROM site WHERE id >= ? AND id <= ? AND type_id = ? ORDER BY id");
            $stmt->bind_param('iii', $pre, $now, $type_id);
            $stmt->execute();
        }
        else {
            $stmt=$mysqli->prepare("SELECT * FROM site WHERE id >= ? AND id <= ? AND type_id = ? ORDER BY id DESC");
            $stmt->bind_param('iii', $now, $pre, $type_id);
            $stmt->execute();
        }
        $result = $stmt->get_result();
        $ids = array();
        $names = array();
        $urls = array();
        while ($row = $result->fetch_assoc()) {
            array_push($ids,$row['id']);
            array_push($names,$row['name']);
            array_push($urls,$row['url']);
        }
        $index_name = $names[0];
        $index_url = $urls[0];
        for ($i=1;$i<count($ids);$i++){
            //printf("UPDATE search SET name = ".$names[$i] .",url=".$urls[$i]." WHERE id = ".$ids[$i-1]."\n");
            $stmt=$mysqli->prepare("UPDATE site SET name = ? ,url = ? WHERE id = ?");
            $stmt->bind_param('ssi', $names[$i], $urls[$i], $ids[$i-1]);
            $stmt->execute();
        }
        //printf("UPDATE search SET name = ".$index_name .",url=".$index_url." WHERE id = ".$now."\n");
        $stmt=$mysqli->prepare("UPDATE site SET name = ? ,url = ? WHERE id = ?");
        $stmt->bind_param('ssi', $index_name, $index_url, $now);
        $stmt->execute();
        $status = 0;
        $msg = "排序成功";
        mysqli_close($mysqli);
    }
    else $msg="你是不是漏了什么？";
}
echo json_encode(array(
    'status' => $status,
    'msg' => $msg
));