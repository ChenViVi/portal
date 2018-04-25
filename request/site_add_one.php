<?php
require_once("../func.php");
$status = 1;
$msg = "出现未知错误";
$data = "";
$type_id = $_GET["type_id"];
$name = $_GET["name"];
$url = $_GET["url"];
$mysqli=new mysqli($DB_HOST,$DB_USER,$DB_PASS,$DB_NAME,$DB_PORT);
$mysqli->set_charset("utf8");
if ($mysqli->connect_errno){
    $msg = "数据库连接失败，请检查配置文件";
}
else{
    if (is_empty($type_id) || is_empty($url) || is_empty($name)){
        $msg = "参数错误";
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
            $stmt=$mysqli->prepare("SELECT * FROM site WHERE url = ?");
            $stmt->bind_param('s', $url);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()) {
                $msg = "此搜索引已经添加过了呢~名称是：" . $row['name'];
            }
            else {
                $stmt=$mysqli->prepare("INSERT INTO site (type_id, name, url) VALUES (?,?,?)");
                $stmt->bind_param('iss', $type_id, $name, $url);
                $stmt->execute();
                $stmt=$mysqli->prepare("SELECT * FROM site WHERE url = ?");
                $stmt->bind_param('s', $url);
                $stmt->execute();
                $result = $stmt->get_result();
                $status = 0;
                $msg = "添加成功";
                $data = $result->fetch_assoc();
            }
        }
    }
    mysqli_close($mysqli);
}
echo json_encode(array(
    'status' => $status,
    'msg' => $msg,
    'data' => $data,
));