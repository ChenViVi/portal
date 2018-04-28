<?php
require_once("../func.php");
$status = 1;
$msg = "出现未知错误，去问问神奇的海螺吧";
$id = $_POST["id"];
$mysqli=new mysqli($DB_HOST,$DB_USER,$DB_PASS,$DB_NAME,$DB_PORT);
$mysqli->set_charset("utf8");
if ($mysqli->connect_errno){
    $msg = "数据库连接失败，请检查 config.php 配置文件";
}
else{
    if (is_empty($id)){
        $msg = "你是不是漏了什么？";
    }
    else{
        $stmt=$mysqli->prepare("SELECT * FROM site_type WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if (!($row = $result->fetch_assoc())){
            $msg = "tan90°_(:3」∠)_";
        }
        else{
            $stmt=$mysqli->prepare("SELECT COUNT(id) FROM site_type");
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            if ($row['COUNT(id)'] == 0) $msg = "说！是不是你动了数据库";
            else if ($row['COUNT(id)'] == 1) $msg = "留一个分类好不好嘛，伦家求求你了嘛~";
            else{
                $stmt=$mysqli->prepare("DELETE FROM site WHERE type_id = ?");
                $stmt->bind_param('i', $id);
                $stmt->execute();
                $stmt=$mysqli->prepare("DELETE FROM site_type WHERE id = ?");
                $stmt->bind_param('i', $id);
                $stmt->execute();
                $status = 0;
                $msg = "删除成功";
            }
        }
    }
    mysqli_close($mysqli);
}
echo json_encode(array(
    'status' => $status,
    'msg' => $msg
));