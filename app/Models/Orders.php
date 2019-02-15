<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Goods newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Goods newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Goods query()
 * @mixin \Eloquent
 */
class Orders extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'order_id';

    const FILES = ['order_id', 'order_good_id', 'order_price', 'order_num', 'order_user', 'order_phone', 'order_address', 'order_user_id', 'order_com_id', 'order_time', 'order_status', 'order_address_regional', 'order_pro', 'order_city', 'order_area', 'order_actual_price', 'order_color_name', 'order_ram_name'];

    /**
     * @param $userid
     * @param $goodid
     * @return 创建大几率不重复的订单号
     */
    public static function createNum($userid, $goodid)
    {
        //订单号码主体（YYYYMMDDHHIISSNNNNNNNN）
        $order_id_main = date('YmdHis') . rand(10000000, 99999999);
        //订单号码主体长度
        $order_id_len = strlen($order_id_main);
        $order_id_sum = 0;
        for ($i = 0; $i < $order_id_len; $i++) {
            $order_id_sum += (int)(substr($order_id_main, $i, 1));
        }
        //唯一订单号码（YYYYMMDDHHIISSNNNNNNNNCC）
        $order_id = $order_id_main . str_pad((100 - $order_id_sum % 100) % 100, 2, '0', STR_PAD_LEFT);
        return md5($userid . $goodid . $_SERVER['REQUEST_TIME'] . $order_id);
    }

    public function test()
    {

    }

}
