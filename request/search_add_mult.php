<?php
require_once("../func.php");
$mysqli=new mysqli($DB_HOST,$DB_USER,$DB_PASS,$DB_NAME,$DB_PORT);
$mysqli->set_charset("utf8");
$name = $_GET["name_1"];
$url = $_GET["url_1"];
$count = $_GET["count"];
if (!is_empty($name) && !is_empty($url) && !is_empty($count)){
    for ($i = 1; $i <=$count; $i++){
        $name = $_GET["name_" . $i];
        $url = $_GET["url_" . $i];
        if (!is_empty($name) && !is_empty($url)){
            $stmt=$mysqli->prepare("INSERT INTO search (name, url) VALUES (?,?)");
            $stmt->bind_param('ss', $name, $url);
            $stmt->execute();
        }
    }
}
?>﻿