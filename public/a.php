<?php
phpinfo();

////连接本地的 Redis 服务
//$redis = new Redis();
//$redis->connect('127.0.0.1', 6379);
////$redis->delete('tutorial-list');
////$redis->rpush("tutorial-list", serialize(['price' => 1, 'amount' => '数量1', 'orderid' => '订单号'])); // 记忆数据
////$redis->rpush("tutorial-list", serialize(['price' => 2, 'amount' => '数量2', 'orderid' => '订单号'])); // 记忆数据
////$redis->rpush("tutorial-list", serialize(['price' => 2, 'amount' => '数量3', 'orderid' => '订单号'])); // 记忆数据
////$redis->rpush("tutorial-list", serialize(['price' => 2, 'amount' => '数量4', 'orderid' => '订单号'])); // 记忆数据
////$redis->rpush("tutorial-list", serialize(['price' => 2, 'amount' => '数量5', 'orderid' => '订单号'])); // 记忆数据
////$redis->rpush("tutorial-list", serialize(['price' => 1, 'amount' => '数量6', 'orderid' => '订单号'])); // 记忆数据
////$redis->rpush("tutorial-list", serialize(['price' => 1, 'amount' => '数量9', 'orderid' => '订单号'])); // 记忆数据
////$redis->rpush("tutorial-list", serialize(['price' => 1, 'amount' => '数量8', 'orderid' => '订单号'])); // 记忆数据
//// 加入redis
////$newsArr[] = ['price' => 14, 'amount' => '数量9', 'orderid' => '订单号'];
////foreach ($newsArr as $k => $v) {
////    $redis->rpush('tutorial-list', serialize($v));
////}
//if (isset($_GET['price']) && isset($_GET['amount'])) {
//    $redis->rPush('tutorial-list', serialize(['price' => $_GET['price'], 'amount' => $_GET['amount'], 'orderid' => '订单号' . rand(1, 20)]));
//}
//$leng   = $redis->lLen('tutorial-list'); // 长度
//$arList = $redis->lrange("tutorial-list", 0, $leng); // 获取数据
//$aims   = [];
//foreach ($arList as $v) {
//    $aims[] = unserialize($v);
//}
//function array_sort($arr, $key, $type = 'desc')
//{
//    $keysvalue = $new_array = array();
//    // 获取key
//    foreach ($arr as $k => $v) {
//        $keysvalue[$k] = $v[$key];
//    }
//    // key排序
//    if ($type == 'asc') {
//        asort($keysvalue);
//    } else {
//        arsort($keysvalue);
//    }
//    reset($keysvalue);
//    // 根据key排序整理正确二维数组顺序
//    foreach ($keysvalue as $k => $v) {
//        $new_array[$k] = $arr[$k];
//        //$new_array[$k]['name'] = '卖' . $k;
//    }
//    return $new_array;
//}
//
//$res = array_sort($aims, 'price', 'desc');
//
//$y = 1;
//foreach ($res as $k => $v) {
//    $res[$k]['name'] = '卖' . $y++;
//}
//
//
//var_dump($res);
//die;
//foreach ($aims as $k => $v) {
//    $num = $v['price'];
//    $res = array_keys($arr, $num);
//// 搜索键
//    $off = $num;
//    if (empty($res)) { // 没有相同值
//        while (!in_array($num, $arr)) {
//            $num--;
//        };
//    }
//    $res = array_keys($arr, $num);
//    //arr排序
//    $next = array_splice($arr, $res[count($res) - 1] + 1);
//    array_unshift($next, $off);
//    $leng = count($arr) - ($res[count($res) - 1] + 1);
//    $prv  = array_splice($arr, $leng);
//    $arr  = array_merge($prv, $next);
//}
//
////// 删除起始值
//array_shift($arr);
//var_dump($arr);
//var_dump($arrc);