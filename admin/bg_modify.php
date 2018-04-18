<?php
require_once("../func.php");
$status = 1;
$msg = "出现未知错误";
$data = array();
$mysqli=new mysqli($DB_HOST,$DB_USER,$DB_PASS,$DB_NAME,$DB_PORT);
$mysqli->set_charset("utf8");
$id = $_GET["id"];
if (!is_empty($id)){
    $stmt=$mysqli->prepare("SELECT * FROM bg WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $name = $row['url'];
    if (!unlink("../bg/" . $name)) {
        $msg = "找不到此文件，删除失败";
        $data = array('url' => "../bg/" . $name);
    }
    else {
        $stmt=$mysqli->prepare("DELETE FROM bg WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $status = 0;
        $msg = "删除成功";
    }
}
else {
    $msg = "参数不全";
}
mysqli_close($mysqli);
echo json_encode(array(
    'status' => $status,
    'msg' => $msg,
    'data' => $data,
));