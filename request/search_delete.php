<?php
require_once("../func.php");
$status = 1;
$msg = "出现未知错误";
$id = $_POST["id"];
$mysqli=new mysqli($DB_HOST,$DB_USER,$DB_PASS,$DB_NAME,$DB_PORT);
$mysqli->set_charset("utf8");
if ($mysqli->connect_errno){
    $msg = "数据库连接失败，请检查 config.php 配置文件";
}
else{
    if (is_empty($id)){
        $msg = "参数错误";
    }
    else{
        $stmt=$mysqli->prepare("SELECT * FROM search WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if (!($row = $result->fetch_assoc())){
            $msg = "tan90°_(:3」∠)_";
        }
        else{
            $stmt=$mysqli->prepare("SELECT COUNT(id) FROM search");
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            if ($row['COUNT(id)'] == 0) $msg = "说！是不是你动了数据库";
            else if ($row['COUNT(id)'] == 1) $msg = "你要删除所有的搜索引擎做什么？杂修";
            else{
                $stmt=$mysqli->prepare("DELETE FROM search WHERE id = ?");
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