<?php
require_once("config.php");
/*check_ip($IP_WHITE_LIST);*/

function check_ip($ip_list){
    $ip = $_SERVER['REMOTE_ADDR'];
    if (! in_array($ip, $ip_list)) {
        header('HTTP/1.0 403 Forbidden');
        die('Your IP address (' . $ip . ') is not authorized to access this file.');
    }
}

function is_empty($C_char){
    if (empty($C_char)) return true; //判断是否已定义字符串
    if ($C_char=='') return true; //判断字符串是否为空
    return false;
}

function utf8_length($str){
    $count = 0;
    if (!empty($str) && $str != ''){
        preg_match_all('/[\x{4e00}-\x{9fa5}]/u', $str, $chinese);
        preg_match_all('/[^\x{4e00}-\x{9fa5}]/u', $str, $string);
        $str_array = array_merge(current($chinese), current($string));
        for($i=0;$i<count($str_array);$i++){
            if (strlen($str_array[$i]) == 1) $count = $count + 0.5;
            else $count = $count + 1;
        }
    }
    return $count;
}

function utf8_substring($str,$length){
    $result = '';
    $count = 0;
    if (!empty($str) && $str != ''){
        preg_match_all('/[\x{4e00}-\x{9fa5}]/u', $str, $chinese);
        preg_match_all('/[^\x{4e00}-\x{9fa5}]/u', $str, $string);
        $str_array = array_merge(current($chinese), current($string));
        for($i=0;$i<count($str_array) && $count < $length;$i++){
            if (strlen($str_array[$i]) == 1){
                $count = $count + 0.5;
            }
            else {
                $count = $count + 1;
            }
            $result = $result . $str_array[$i];
        }
    }
    return $result;
}

function is_url($url) {
    return !is_empty(parse_url($url,PHP_URL_SCHEME));
}