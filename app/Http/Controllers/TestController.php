<?php

namespace App\Http\Controllers;

use App\Mail\OrderShipped;
use App\Models\Groups;
use App\Models\Orders;
use App\Models\UserAddress;
use App\Models\Users;
use App\User;
use App\Models\Goods;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Jobs\Good;
use Log;
use Illuminate\Support\Facades\Schema;
use Mail;
use App\Jobs\SeedMail;
use Cache;
use App\Jobs\GroupCreate;
use Validator;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

class TestController extends Controller
{
    public function index(Request $request)
    {
//        //array_splice()  array_search()
//        $arr = [0, 1, 2, 3, 3, 4, 5, 6, 7];
//        $res = array_keys($arr, 10);
//
//        var_dump($res);
//        $next = array_splice($arr, $res[count($res) - 1] + 1);
//        array_unshift($next, 3); // 添加元素
//        var_dump($next); // 之后的数据
//        $leng = count($arr) - ($res[count($res) - 1] + 1);
//        $prv  = array_splice($arr, $leng);
//        var_dump($prv); // 之前的数据
//        var_dump(array_merge($prv, $next));
//        die;

        $num = 7;
        $arr = [0, 1, 2, 3, 3, 4, 5, 6, 8];
        $res = array_keys($arr, $num);
        if (empty($res)) {  // 没有相同值
            $off = $num;
            while (!in_array($num, $arr)) {
                $num--;
            };
            // 得到上一级分成

            $res  = array_keys($arr, $num);
            $next = array_splice($arr, $res[count($res) - 1] + 1);
            array_unshift($next, $off); // 添加元素
            var_dump($next); // 之后的数据
            $leng = count($arr) - ($res[count($res) - 1] + 1);
            $prv  = array_splice($arr, $leng);
            var_dump($prv); // 之前的数据
            var_dump(array_merge($prv, $next));

        }


    }

    public function index2()
    {
        $arr     = [];
        $foreach = array_fill(0, 20, 'test'); // 200
        foreach ($foreach as $k => $v) {
            $num = rand(1, 30);    // 随机数
            if (empty($arr)) {        // 初始化
                $arr[] = $num;
            } else {
                $res = array_keys($arr, $num);
                // 搜索键
                if (empty($res)) { // 没有相同值
                    $off = $num;
                    while (!in_array($num, $arr)) {
                        $num--;
                    };
                    $res  = array_keys($arr, $num);
                    $next = array_splice($arr, $res[count($res) - 1] + 1);
                    array_unshift($next, $off); // 添加元素
                    $leng = count($arr) - ($res[count($res) - 1] + 1);
                    $prv  = array_splice($arr, $leng);
                    $arr = array_merge($prv, $next);
                } else { // 有相同值
                    $res = array_keys($arr, $num);
                    $next = array_splice($arr, $res[count($res) - 1] + 1);
                    array_unshift($next, $num); // 添加元素
                    $leng = count($arr) - ($res[count($res) - 1] + 1);
                    $prv  = array_splice($arr, $leng);
                    $arr = array_merge($prv, $next);
                }
            }
        }


        var_dump($arr);
    }


    public function mail()
    {
        $time       = now()->addHour(5);
        $good       = Goods::find(11)->toArray();
        $good['to'] = '1946431302@qq.com';
        SeedMail::dispatch($good)->delay($time);
    }

}
