<?php

namespace App\Http\Controllers\v1;

use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Jobs\SeedMail;

class UserController extends Controller
{

    /**
     * 注册用户
     * @param Request $request
     * @return string
     */
    public function sign(Request $request)
    {
        $email = $request->input('email');
        $user  = Cache::remember($email, 100, function () use ($email) {
            return User::where('user_email', $email)->first();
        });
        $all   = $request->input();
        if (!is_null($user)) {
            return self::error('已存在');
        }
        $url          = route('userActivation', ['code' => encrypt($email)]);// 消息
        $data['to']   = $email;
        $data['data'] = '<a  href="' . $url . '">点击激活邮箱</a>';
        SeedMail::dispatch($data)->onQueue('UserSign');  // 分发队列
        Cache::put($email, serialize($all), 5);      // 设置5分钟过期
        return self::success('成功');
    }

    /**
     * 激活账户
     * @param Request $request
     * @return string
     */
    public function activation(Request $request)
    {
        $email = $request->input('code');
// 解密
        try {
            $email = decrypt($email);
        } catch (DecryptException $e) {
            return self::error('激活邮箱失败');
        }
// 检查缓存是否存在
        if (Cache::has($email) === false) {
            return self::error('该认证已经失效');
        }
// 查询是否存在一个邮箱
        $user = Cache::remember('hasEmail', 100, function () use ($email) {
            return User::where('user_email', $email)->first();
        });
        if (!is_null($user)) {
            return self::error('已存在');
        }
// 准备数据
        $data = Cache::get($email);
        $data = unserialize($data);
        $bool = User::insert([
            'user_email' => $data['email'],
            'user_password' => encrypt($data['password']),
            'user_time' => $_SERVER['REQUEST_TIME'],
            'user_status' => 1,
        ]);
        if ($bool) {
            Cache::forget($email);    // 清除缓存
            Cache::forget('hasEmail');// 清除缓存
            return self::success('成功');
        }
        return self::error('失败');
    }

    /**
     * 用户登录
     * @param Request $request
     * @return string
     */
    public function login(Request $request)
    {
        $all = $request->all();
        //$name = $all['email'] . 'login';
        $user = User::select(['user_id', 'user_password'])->where('user_email', $all['email'])->first();
        if (is_null($user)) {
            return self::error('账号不存在');
        }
        try {
            $password = decrypt($user->user_password);
        } catch (DecryptException $e) {
            return self::error('密码解析错误');
        }
        if ($password == $all['password']) {
            session(['user_id' => $user->user_id]); // 记录ID
            return self::success('成功');
        }
        return self::error('失败');
    }

}
