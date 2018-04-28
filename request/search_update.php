<?php
require_once("../func.php");
$status = 1;
$msg = "出现未知错误，去问问神奇的海螺吧";
$data = "";
$id = $_POST["id"];
$name = $_POST["name"];
$url = $_POST["url"];
$mysqli=new mysqli($DB_HOST,$DB_USER,$DB_PASS,$DB_NAME,$DB_PORT);
$mysqli->set_charset("utf8");
if ($mysqli->connect_errno){
    $msg = "数据库连接失败，请检查 config.php 配置文件";
}
else if (is_empty($id) ||is_empty($name) || is_empty($url)){
    $msg = "你是不是漏了什么？";
}
else if (utf8_length($name) > 6) {
    $msg = "插入这么长的东西的话，数据库酱会痛得受不了的哟~";
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
        $stmt=$mysqli->prepare("SELECT * FROM search WHERE url = ?");
        $stmt->bind_param('s', $url);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()){
            if ($row['id'] != $id) $msg = "对应此链接的搜索引擎已经存在了哟你个小忘事鬼~名称是：" . $row['name'];
            else {
                if ($row['name'] == $name) $msg = "啥变化都米有惹";
                else {
                    $stmt=$mysqli->prepare("UPDATE search SET name = ?, url = ? WHERE id = ?");
                    $stmt->bind_param('ssi', $name, $url, $id);
                    $stmt->execute();
                    $status = 0;
                    $msg = "修改成功";
                    $data = array(
                        'id' => $id,
                        'name' => $name,
                        'url' => $url);
                }
            }
        }
        else {
            $stmt=$mysqli->prepare("UPDATE search SET name = ?, url = ? WHERE id = ?");
            $stmt->bind_param('ssi', $name, $url, $id);
            $stmt->execute();
            $status = 0;
            $msg = "修改成功";
            $data = array(
                'id' => $id,
                'name' => $name,
                'url' => $url);
        }
    }
}
mysqli_close($mysqli);
echo json_encode(array(
    'status' => $status,
    'msg' => $msg,
    'data' => $data
));