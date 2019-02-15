<?php

namespace App\Http\Controllers\V1;

use App\Models\UserAddress;
use App\Rules\Name;
use App\Rules\Phone;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class AddressController extends Controller
{

    /**
     * 获取收货地址
     * @return string
     */
    public function get()
    {
        $address = UserAddress::getByUserId(session('user_id', 1))->toArray();
        foreach ($address as $k => $v) {
            $address[$k]['address_phone'] = UserAddress::hidePhone($v['address_phone']);
            $address[$k]['address']       = $v['address_province'] . $v['address_city'] . $v['address_area'] . $v['address_info'];
        }
        return self::success($address);
    }

    /**
     * 更新默认地址
     * @param Request $request
     * @return string
     */
    public function updateDefault(Request $request)
    {
        $bool = UserAddress::updateDefault(session('user_id', 1), $request->input('id'));
        if ($bool) {
            $name = 'userAddress' . session('user_id', 1) . 1;
            Cache::forget($name);
            return self::success();
        }
        return self::error();
    }

    /**
     * 增加一个收货地址
     * @param Request $request
     */
    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', new Name()],
            'phone' => ['required', new Phone()],
            'pca' => ['required'],
            'address' => ['required']
        ]);
        if ($validator->fails()) {
            $error = collect($validator->errors())->toArray();
            return self::error($error[key($error)][0]);
        }
        $pca  = str_replace(' ', '/', $request->input('pca'));
        $bool = UserAddress::add(session('user_id', 1), [
            'address_name' => $request->input('name'),
            'address_phone' => $request->input('phone'),
            'address_province' => explode('/', $pca)[0],
            'address_city' => explode('/', $pca)[1],
            'address_area' => explode('/', $pca)[2]
        ]);
        if ($bool) {
            return self::success();
        } else {
            return self::error();
        }
    }

    /**
     * 删除一个默认地址
     * @param Request $request
     * @return string
     */
    public function del(Request $request)
    {
        $bool = UserAddress::del(session('user_id', 1), $request->input('id'));
        if ($bool) {
            return self::success();
        }
        return self::error();
    }

}
