<?php

namespace App\Http\Controllers\V1;

use App\Models\Orders;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Models\Goods;
use App\Models\Groups;
use App\Jobs\OrderCreate;

class OrderController extends Controller
{
    /**
     * 确认支付信息
     * @param Request $request
     */
    public function ready(Request $request)
    {
        //Cache::flush();
        $userid = $request->input('userid');
// 获取默认收货地址
        $name    = 'userAddress' . $userid . 1;
        $address = Cache::remember($name, cacheTime(), function () use ($userid) {
            return UserAddress::getDefaultByUserid($userid);
        });
        if (is_null($address)) {
            return self::error('无默认收货地址');
        }
        $address->address_phone = UserAddress::hidePhone($address->address_phone);
// 商品信息
        $goodid = $request->input('goodid');
        $good   = Cache::remember('goodBase' . $goodid, 10, function () use ($goodid) {
            return Goods::getBaseById($goodid);
        });
        if (is_null($good)) {
            return self::error('商品不存在或者已经下架');
        }
        return self::success([
            'address' => [
                'name' => $address->address_name,
                'phone' => $address->address_phone,
                'address' => $address->address_province . $address->address_city . $address->address_area . $address->address_info,
            ],
            'good' => [
                'price' => $good->good_price,
                'fare' => $good->good_fare,
                'name' => $good->good_title,
                'img' => $good->good_image,
            ],
            'pay' => $good->good_price + $good->good_fare,
        ]);
    }

    /**
     * 创建订单
     * @param Request $request
     */
    public function create(Request $request)
    {
        $all = $request->all();
        if ($request->has('groupid')) { // 加入拼团


        } else { // 创建拼团
// 检查是否创建拼团
            $name  = $all['userid'] . 'group' . $all['goodid'] . 'has';
            $group = Cache::remember($name, 20, function () use ($all) {
                $data = Groups::where(['group_user_id' => $all['userid'], 'group_good_id' => $all['goodid']])
                    ->where(['group_pay' => 0])
                    ->orderBy('group_id', 'desc')->first(['group_id']);
                return is_null($data) ? false : $data->group_id;
            });
            if ($group) {
                return self::error('拼团创建失败');
            }
// 获取默认收货地址
            $name    = 'userAddress' . $all['userid'] . 1;
            $address = Cache::remember($name, cacheTime(), function () use ($all) {
                return UserAddress::getDefaultByUserid($all['userid']);
            });
            if (is_null($address)) {
                return self::error('无默认收货地址');
            }
// 获取商品信息
            $goodid = $request->input('goodid');
            $good   = Cache::remember('goodBase' . $goodid, 10, function () use ($goodid) {
                return Goods::getBaseById($goodid);
            });
            if (is_null($good)) {
                return self::error('商品不存在或者已经下架');
            }
// 准备订单数据
            $data['order_num']      = Orders::createNum($all['userid'], $all['goodid']);
            $data['order_group_id'] = $group;
            $data['order_pay']      = 0;
            $data['order_time']     = $_SERVER['REQUEST_TIME'];
            $data['order_receipt']  = $address->address_info;
            $data['order_area']     = $address->address_area;
            $data['order_city']     = $address->address_city;
            $data['order_province'] = $address->address_province;
            $data['order_fare']     = $good->good_fare;
            $data['order_price']    = $good->good_price + $good->good_fare;
            $data['order_user_id']  = $all['userid'];
            $data['order_good_id']  = $all['goodid'];
// 分发队列任务
            OrderCreate::dispatch($data)->onQueue('OrderCreate');
            return self::success($data['order_num']);
        }
    }




    /**
     * 用户订单
     * @param Request $request
     * @return string
     */
    public function orders(Request $request)
    {
        $where['order_user_id'] = session('user_id', 1);
        if ($request->has('pay')) {
            $where['order_status'] = $request->input('pay');
        }
        $orders = Orders::where($where)
            ->forPage($request->input('page', 1), $request->input('limit', 10))
            ->orderBy('order_time', 'desc')
            ->select()->get();
        return self::success($orders->toArray());
    }

}
