<?php
require_once("../func.php");
$mysqli=new mysqli($DB_HOST,$DB_USER,$DB_PASS,$DB_NAME,$DB_PORT);
$mysqli->set_charset("utf8");
$pre = $_GET["start"];
$now = $_GET["end"];
$type_id = $_GET["type_id"];
if (!is_empty($pre) && !is_empty($now && $pre!=$now)){
    if ($pre < $now){
        $stmt=$mysqli->prepare("SELECT * FROM site WHERE id >= ? AND id <= ? AND type_id = ? ORDER BY id");
        $stmt->bind_param('iii', $pre, $now, $type_id);
        $stmt->execute();
    }
    else {
        $stmt=$mysqli->prepare("SELECT * FROM site WHERE id >= ? AND id <= ? AND type_id = ? ORDER BY id DESC");
        $stmt->bind_param('iii', $now, $pre, $type_id);
        $stmt->execute();
    }
    $result = $stmt->get_result();
    $ids = array();
    $names = array();
    $urls = array();
    while ($row = $result->fetch_assoc()) {
        array_push($ids,$row['id']);
        array_push($names,$row['name']);
        array_push($urls,$row['url']);
    }
    $index_name = $names[0];
    $index_url = $urls[0];
    for ($i=1;$i<count($ids);$i++){
        $stmt=$mysqli->prepare("UPDATE site SET name = ? ,url = ? WHERE id = ? AND type_id = ?");
        $stmt->bind_param('ssii', $names[$i], $urls[$i], $ids[$i-1], $type_id);
        $stmt->execute();
    }
    $stmt=$mysqli->prepare("UPDATE site SET name = ? ,url = ? WHERE id = ? AND type_id = ?");
    $stmt->bind_param('ssii', $index_name, $index_url, $now, $type_id);
    $stmt->execute();
}