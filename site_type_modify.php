<?php
require_once("config.php");
require_once("func.php");
check_ip($ip_white_list);
$mysqli=new mysqli($DB_HOST,$DB_USER,$DB_PASS,$DB_NAME,$DB_PORT);
$mysqli->set_charset("utf8");
$id = $_GET["id"];
$name = $_GET["name"];
if (!is_empty($name)){
    if (is_empty($id)){
        $stmt=$mysqli->prepare("INSERT INTO site_type (name) VALUES (?)");
        $stmt->bind_param('s', $name);
        $stmt->execute();
    }
    else{
        $stmt=$mysqli->prepare("UPDATE site_type SET name = ? WHERE id = ?");
        $stmt->bind_param('si', $name, $id);
        $stmt->execute();
        echo "fic";
    }
}
else{
    if (!is_empty($id)){
        $stmt=$mysqli->prepare("DELETE FROM site_type WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
    }
}