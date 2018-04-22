<?php
require_once("../func.php");
$status = 1;
$msg = "出现未知错误";
$data = "";
$id = $_GET["id"];
$name = $_GET["name"];
$mysqli=new mysqli($DB_HOST,$DB_USER,$DB_PASS,$DB_NAME,$DB_PORT);
$mysqli->set_charset("utf8");
if ($mysqli->connect_errno){
    $msg = "数据库连接失败，请检查配置文件";
}
else{
    if (is_empty($id) ||is_empty($name)){
        $msg = "参数错误";
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
            $stmt=$mysqli->prepare("UPDATE site_type SET name = ? WHERE id = ?");
            $stmt->bind_param('si', $name, $id);
            $stmt->execute();
            $status = 0;
            $msg = "修改成功";
            $data = array(
                'id' => $id,
                'name' => $name);
        }
    }
    mysqli_close($mysqli);
}
echo json_encode(array(
    'status' => $status,
    'msg' => $msg,
    'data' => $data
));