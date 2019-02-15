<?php

namespace App\Http\Controllers\V1;

use App\Models\Bananas;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class BananaController extends Controller
{
    /**
     * 获取轮播图
     * @return string
     */
    public function get()
    {
        $banana = Cache::remember('bananas', cacheTime(20), function () {
            return Bananas::select(['banana_img', 'banana_href'])
                ->where(['banana_status' => 1])
                ->orderBy('banana_time', 'desc')
                ->limit(10)
                ->get();
        });
        return self::success($banana->toArray());
    }


}
