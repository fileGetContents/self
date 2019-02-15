<?php

use Illuminate\Database\Seeder;
use App\Models\Goods;

class Good extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (range(1, 10) as $value) {
            Goods::create([
                'good_title' => '蒙自石榴水果*2个' . $value,
                'good_info' => '粉色玛瑙 天生丽质 好水果' . $value,
                'good_images' =>serialize(['themes/img/banner/goods-002.png', 'themes/img/banner/goods-003.png', 'themes/img/banner/goods-004.png']),
                'good_image' => 'themes/img/ad/ad-003.jpg',
                'good_price' => 9.9,
                'good_market_price' => 29.9,
                'good_end_time' => $_SERVER['REQUEST_TIME'] + 24 * 60 * 60 * 30,
                'good_participation' => 1000,
                'good_people' => rand(1, 5),
                'good_text' => '详细页面' . $value,
                'good_time' => $_SERVER['REQUEST_TIME'],
                'good_status' => 1,
                'good_place' => 1
            ]);
        }
    }
}
