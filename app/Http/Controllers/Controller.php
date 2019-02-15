<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    const V1 = 'v1_';

    /**
     * @param $data
     * @return string
     */
    public static function success($data = '')
    {
        return json_encode(['msg' => 'ok', 'data' => $data], JSON_UNESCAPED_UNICODE);
    }

    /**
     * @param $data
     * @return string
     */
    public static function error($data = '')
    {
        return json_encode(['msg' => 'error', 'data' => $data], JSON_UNESCAPED_UNICODE);
    }

    /**
     * 加入版本号的名称
     * @param $name
     * @return string
     */
    public static function v1name($name)
    {
        return static::V1 . $name;
    }


}
