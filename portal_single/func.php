<?php

function is_empty($C_char){
    if (empty($C_char)) return true; //判断是否已定义字符串
    if ($C_char=='') return true; //判断字符串是否为空
    return false;
}

function check_ip($ip_list){
    $userIp = $_SERVER['REMOTE_ADDR'];
    if (! in_array($userIp, $ip_list)) {
        header('HTTP/1.0 403 Forbidden');
        die('Your IP address (' . $userIp . ') is not authorized to access this file.');
    }
}

function redirect($url){
    echo "<script type='text/javascript'>";
    echo "window.location.href='$url'";
    echo "</script>";
}