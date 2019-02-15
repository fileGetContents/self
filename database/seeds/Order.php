<?php

use Illuminate\Database\Seeder;
use App\Models\Orders;

class Order extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (range(1, 20000) as $value) {

            $gid = rand(1, 30);
            $uid = rand(1, 30);
            $num = Orders::createNum($uid, $gid);
            Orders::insert([
                'order_good_id' => $gid,
                'order_user_id' => $uid,
                'order_price' => rand(10, 30) . '.0' . rand(1, 8),
                'order_fare' => 0,
                'order_province' => '四川省',
                'order_city' => '成都市',
                'order_area' => '武侯区',
                'order_receipt' => '新生路5号',
                'order_time' => $_SERVER['REQUEST_TIME'],
                'order_pay' => 10,
                'order_group_id' => 0,
                'order_num' => $num,
            ]);
        }
    }
}
