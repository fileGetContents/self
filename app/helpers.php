<?php
/**
 * 创建一个缓存随机数
 * @param $value int 缓存时间
 * @param int $limit 偏移量
 * @return int
 */
function cacheTime($value = 10, $limit = 3)
{
    return rand($value - $limit, $value + $limit);
}