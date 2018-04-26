<?php
require_once("../func.php");
$status = 1;
$msg = "出现未知错误";
$data = "";
switch ($_FILES["file"]["error"]){
    case 0:
        $allowed = array("gif", "jpeg", "jpg", "png");
        $temp = explode(".", $_FILES["file"]["name"]);
        $extension = end($temp);
        if ((($_FILES["file"]["type"] != "image/gif")
                && ($_FILES["file"]["type"] != "image/jpeg")
                && ($_FILES["file"]["type"] != "image/jpg")
                && ($_FILES["file"]["type"] != "image/pjpeg")
                && ($_FILES["file"]["type"] != "image/x-png")
                && ($_FILES["file"]["type"] != "image/png"))
            || !in_array($extension, $allowed)) {
            $msg = "非法的文件格式";
        }
        else {
            $mysqli=new mysqli($DB_HOST,$DB_USER,$DB_PASS,$DB_NAME,$DB_PORT);
            $mysqli->set_charset("utf8");
            if ($mysqli->connect_errno){
                $msg = "数据库连接失败，请检查 config.php 配置文件";
            }
            else {
                $filename = time();
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
        mysqli_close($mysqli);
        break;
    case 1 : $msg = "图片大小超过了 php.ini 中 upload_max_filesize 选项限制的值"; break;
    case 2 : $msg = "图片大小超过了 HTML 表单中 MAX_FILE_SIZE 选项指定的值"; break;
    case 3 : $msg = "图片只有部分被上传"; break;
    case 4 : $msg = "没有图片被上传"; break;
    case 6 : $msg = "找不到临时文件夹"; break;
    case 7 : $msg = "文件写入失败"; break;
}
echo json_encode(array(
    'status' => $status,
    'msg' => $msg,
    'data' => $data,
));
