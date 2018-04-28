<?php
require_once("../func.php");
$status = 1;
$msg = "出现未知错误，去问问神奇的海螺吧";
$data = "";
$mysqli=new mysqli($DB_HOST,$DB_USER,$DB_PASS,$DB_NAME,$DB_PORT);
$mysqli->set_charset("utf8");
if ($mysqli->connect_errno){
    $msg = "数据库连接失败，请检查 config.php 配置文件";
}
else {
    $id = $_POST["id"];
    if (!is_empty($id)){
        $stmt=$mysqli->prepare("SELECT * FROM bg WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $name = $row['url'];
        if (!unlink("../bg/" . $name)) {
            $msg = "tan90°_(:3」∠)_";
        }
        else {
            $stmt=$mysqli->prepare("DELETE FROM bg WHERE id = ?");
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $stmt=$mysqli->prepare("SELECT * FROM bg ORDER BY rand() limit 1");
            $stmt->execute();
            $result = $stmt->get_result();
            $status = 0;
            $msg = "删除成功";
            $data = $result->fetch_assoc();
        }
    }
    else {
        $msg = "参数不全";
    }
}
mysqli_close($mysqli);
echo json_encode(array(
    'status' => $status,
    'msg' => $msg,
    'data' => $data,
));