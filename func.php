<?php

function is_empty($C_char){
    if (empty($C_char)) return true; //判断是否已定义字符串
    if ($C_char=='') return true; //判断字符串是否为空
    return false;
}

function check_ip($ip_list){
    $ip = $_SERVER['REMOTE_ADDR'];
    if (! in_array($ip, $ip_list)) {
        header('HTTP/1.0 403 Forbidden');
        die('Your IP address (' . $ip . ') is not authorized to access this file.');
    }
}