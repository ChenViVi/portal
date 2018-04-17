<?php
$status = 1;
$msg = "出现未知错误";
$data = array();
require_once("../func.php");
$mysqli=new mysqli($DB_HOST,$DB_USER,$DB_PASS,$DB_NAME,$DB_PORT);
$mysqli->set_charset("utf8");
$allowed = array("gif", "jpeg", "jpg", "png");
$temp = explode(".", $_FILES["file"]["name"]);
$extension = end($temp);     // 获取文件后缀名
if ((($_FILES["file"]["type"] == "image/gif")
        || ($_FILES["file"]["type"] == "image/jpeg")
        || ($_FILES["file"]["type"] == "image/jpg")
        || ($_FILES["file"]["type"] == "image/pjpeg")
        || ($_FILES["file"]["type"] == "image/x-png")
        || ($_FILES["file"]["type"] == "image/png"))
    && in_array($extension, $allowed)) {
	if ($_FILES["file"]["error"] > 0) {
        $msg = $_FILES["file"]["error"];
	}
	else {
	    $filename = time() . "_" . $_FILES["file"]["name"];
        move_uploaded_file($_FILES["file"]["tmp_name"], "../bg/" . $filename);
        $stmt=$mysqli->prepare("INSERT INTO bg (url) VALUES (?)");
        $stmt->bind_param('s', $filename);
        $stmt->execute();
        if ($stmt->get_result()){
            $msg = "错误的数据库操作";
        }
        else{
            $stmt=$mysqli->prepare("SELECT * FROM bg WHERE url = ? ORDER BY id");
            $stmt->bind_param('s', $filename);
            $stmt->execute();
            $status = $stmt->get_result();
            $row = $status->fetch_assoc();
            $status = 0;
            $msg = "添加成功";
            $data = array(
                'id' => $row['id'],
                'url' => $row['url']
            );
        }
	}
}
else {
    $msg = "非法的文件格式";
}
mysqli_close($mysqli);
echo json_encode(array(
    'status' => $status,
    'msg' => $msg,
    'data' => $data,
));
