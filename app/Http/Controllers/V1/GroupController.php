<?php

namespace App\Http\Controllers\V1;

use App\Models\Groups;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Jobs\GroupCreate;
use App\Models\Goods;
use Illuminate\Support\Facades\Log;

class GroupController extends Controller
{
    /**
     * 获取商品对应的分组
     * @param Request $request
     * @return string
     */
    public function goodById(Request $request)
    {
        $name = 'GroupGoodById' . $request->input('id');
        $id   = $request->input('id');
        //Cache::forget($name);
        $group = Cache::remember($name, cacheTime(5), function () use ($id) {
            return Groups::getGroupByGoodId($id);
        });
        return self::success($group->toArray());
    }

    /**
     * 创建拼团
     * @param Request $request
     * @return string
     */
    public function create(Request $request)
    {
        $goodid = $request->input('id'); // 20     400条数数据
        $userid = rand(1, 20); // 20
        // 获取商品缓存
        $name = 'goodBase' . $goodid;
        $good = Cache::remember($name, 10, function () use ($goodid) {
            return Goods::getBaseById($goodid);
        });
        // 检查是否还可以创建拼团
        if (Groups::isGroupByGood($goodid, $userid) === false) {
            Log::channel('group')->info('U:' . $userid . '     G:' . $goodid . '已经拥有');// 创建
            return self::error('已经存在一个拼团了' . 'U:' . $userid . '     G:' . $goodid);
        };

        $data['group_user_id']  = $userid; // user_id
        $data['group_good_id']  = $goodid; // good_id
        $data['group_time']     = $_SERVER['REQUEST_TIME'];
        $data['group_end_time'] = $_SERVER['REQUEST_TIME'] + 432000; // 5天结束
        $data['group_number']   = $good->good_people - 1; // 期望人数
        $data['group_pay']      = 1;
        GroupCreate::dispatch($data)->onQueue('GroupCreate');// 分发队列任务
        return self::success(['gid' => $goodid, 'uid' => $userid]);
    }

    /**
     * 是否创建一个拼团
     * @param Request $request
     * @return string
     */
    public function hasGroup(Request $request)
    {
        $group = Groups::where(['group_user_id' => $request->input('userid'), 'group_good_id' => $request->input('group_good_id')])
            ->where(['group_pay' => 0])
            ->orderBy('group_id', 'desc')->first();
        if (is_null($group)) {
            return self::error('订单创建失败');
        }
        return self::success('订单创建失败');
    }

}
