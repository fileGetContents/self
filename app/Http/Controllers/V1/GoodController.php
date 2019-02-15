<?php

namespace App\Http\Controllers\V1;

use App\Models\Goods;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\StoreBlogPost;

class GoodController extends Controller
{
    /**
     * 获取首页10个推荐位置
     * @return string
     */
    public function getHomeList()
    {
        $goods = Cache::remember('homeList', cacheTime(), function () {
            return $goods = Goods::select(['good_id', 'good_image', 'good_title', 'good_info', 'good_price', 'good_market_price'])
                ->where(['good_place' => 1, 'good_status' => 1])
                ->orderBy('good_time', 'desc')
                ->limit(10)
                ->get();
        });
        return self::success($goods->toArray());
    }


    /**
     * 获取商品基本信息
     * @param Request $request
     * @return string
     */
    public function getBaseInfo(Request $request)
    {
        $id   = $request->input('good_id');
        $name = 'goodBase' . $id;
        //Cache::delete($name);
        $good = Cache::remember($name, cacheTime(), function () use ($id) {
            return Goods::getBaseById($id);
        });
        if (is_null($good)) {
            return self::error('不存在商品');
        }
        return self::success($good->toArray());
    }

    /**
     * 分页
     * @param Request $request
     * @return string
     */
    public function page(Request $request)
    {
        $limit = $request->input('limit', 15);
        //Cache::delete('goodPage' . $limit);
        $good = Cache::remember('goodPage' . $limit, cacheTime(30), function () use ($limit) {
            return Goods::where(['good_status' => 1])
                ->select(['good_id','good_people', 'good_image', 'good_title', 'good_info', 'good_price', 'good_market_price'])
                ->orderBy('good_time', 'desc')
                ->simplePaginate($limit);
        });
        return self::success($good->toArray());
    }

}
