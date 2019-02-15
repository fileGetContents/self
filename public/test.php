<?php

function arraySort($get, $key = 'price', $type = 'desc')
{
    $redis = new Redis();
    $redis->connect('127.0.0.1', 6379);
    if (isset($get['price']) && isset($get['amount'])) {
        $redis->rPush('tutorial-list', serialize($get));
    }
    $leng   = $redis->lLen('tutorial-list');
    $arList = $redis->lrange("tutorial-list", 0, $leng);
    $arr    = [];
    foreach ($arList as $v) {
        $arr[] = unserialize($v);
    }
    $keysvalue = $new_array = array();
    foreach ($arr as $k => $v) {
        $keysvalue[$k] = $v[$key];
    }
    if ($type == 'asc') {
        asort($keysvalue);
    } else {
        arsort($keysvalue);
    }
    reset($keysvalue);
    foreach ($keysvalue as $k => $v) {
        $new_array[$k] = $arr[$k];
    }
    $y = 1;
    foreach ($new_array as $k => $v) {
        $new_array[$k]['name'] = '卖' . $y++;
    }
    return $new_array;
}

$get['price']  = $_GET['price'];
$get['amount'] = $_GET['amount'];
// more
var_dump(arraySort($get));


