<?php
require_once("func.php");
$mysqli=new mysqli($DB_HOST,$DB_USER,$DB_PASS,$DB_NAME,$DB_PORT);
$mysqli->set_charset("utf8");
$pre = $_GET["start"];
$now = $_GET["end"];
if (!is_empty($pre) && !is_empty($now && $pre!=$now)){
    if ($pre < $now){
        $stmt=$mysqli->prepare("SELECT * FROM search WHERE id >= ? AND id <= ?");
        $stmt->bind_param('ii', $pre, $now);
        $stmt->execute();
    }
    else {
        $stmt=$mysqli->prepare("SELECT * FROM search WHERE id >= ? AND id <= ? ORDER BY id DESC");
        $stmt->bind_param('ii', $now, $pre);
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
        //printf("UPDATE search SET name = ".$names[$i] .",url=".$urls[$i]." WHERE id = ".$ids[$i-1]."\n");
        $stmt=$mysqli->prepare("UPDATE search SET name = ? ,url = ? WHERE id = ?");
        $stmt->bind_param('ssi', $names[$i], $urls[$i], $ids[$i-1]);
        $stmt->execute();
    }
    //printf("UPDATE search SET name = ".$index_name .",url=".$index_url." WHERE id = ".$now."\n");
    $stmt=$mysqli->prepare("UPDATE search SET name = ? ,url = ? WHERE id = ?");
    $stmt->bind_param('ssi', $index_name, $index_url, $now);
    $stmt->execute();
}