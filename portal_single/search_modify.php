<?php
require_once("config.php");
require_once("func.php");
check_ip($ip_white_list);
$mysqli=new mysqli($DB_HOST,$DB_USER,$DB_PASS,$DB_NAME,$DB_PORT);
$mysqli->set_charset("utf8");
$id = $_GET["id"];
$name = $_GET["name_1"];
$url = $_GET["url_1"];
if (!is_empty($name) && !is_empty($url)){
    if (is_empty($id)){
        $i = 1;
        while (!is_empty($name)){
            $stmt=$mysqli->prepare("INSERT INTO search (name, url) VALUES (?,?)");
            $stmt->bind_param('ss', $name, $url);
            $stmt->execute();
            $name = $_GET["name_" . $i++];
            $url = $_GET["url_" . $i];
        }
    }
    else {
        $stmt=$mysqli->prepare("UPDATE search SET name = ?, url = ? WHERE id = ?");
        $stmt->bind_param('ssi', $name, $url, $id);
        $stmt->execute();
    }
}
else{
    if (!is_empty($id)){
        $stmt=$mysqli->prepare("DELETE FROM search WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
    }
}
redirect("search.php");
?>﻿