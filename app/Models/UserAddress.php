<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Goods newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Goods newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Goods query()
 * @mixin \Eloquent
 */
class UserAddress extends Model
{
    use SoftDeletes;
    public $table = 'user_address';
    public $fillable = ['address_user_id', 'address_info', 'address_province', 'address_city', 'address_area', 'address_status', 'address_name', 'address_phone'];
    protected $primaryKey = 'address_id';
    protected $dates = ['deleted_at'];
    public $timestamps = false;
    const FILES = ['address_id', 'address_user_id', 'address_info', 'address_province', 'address_city', 'address_area', 'address_status', 'address_name', 'address_phone'];

    /**
     * @param $userid
     * @return 获取默认收货地址
     */
    public static function getDefaultByUserid($userid)
    {
        return static::where(['address_user_id' => $userid])
            ->orderBy('address_status', 'desc')
            ->first(['address_phone', 'address_name', 'address_info', 'address_province', 'address_city', 'address_area']);
    }

    /**
     * @param $value
     * @return 展示部分电话号码
     */
    public static function hidePhone($value)
    {
        return substr($value, 0, 3) . '****' . substr($value, 7);
    }

    /**
     * 获取用户全部收货地址
     * @param $id
     * @return 获取用户全部收货地址
     */
    public static function getByUserId($userid)
    {
        return static::where(['address_user_id' => $userid])
            ->orderBy('address_status', 'desc')
            ->limit(5)->select(static::FILES)->get();
    }

    /**
     * @param $userid
     * @param $id
     * @return 更新默认收货地址
     */
    public static function updateDefault($userid, $id)
    {
        $default = static::where(['address_user_id' => $userid, 'address_id' => $id])
            ->select(['address_status'])
            ->first();
        if (is_null($default)) {
            return false;
        }
        if ($default->address_status == 1) {
            return true;
        }
        DB::transaction(function () use ($userid, $id) {
            static::where(['address_user_id' => $userid])->update(['address_status' => 0]);
            static::where(['address_user_id' => $userid, 'address_id' => $id])->update(['address_status' => 1]);
        });
        return true;
    }

    /**
     * @param $userid
     * @param $data
     * @return 添加一个收货地址
     */
    public static function add($userid, $data)
    {
        if (Cache::has('UserAddressNum' . $userid)) {
            $num = Cache::get('UserAddressNum' . $userid);
        } else {
            $num = static::where('address_user_id', '=', $userid)->count('address_id');
        };
        if ($num >= 5) {
            return false;
        }
        if ($num == 0) {
            $data['address_status'] = 1;
        }
        $data['address_user_id'] = $userid;
        Cache::delete('UserAddressNum' . $userid);// 删除缓存
        return static::insert($data);
    }

    /**
     * @param $userid
     * @param $id
     * @return 删除一个收货地址
     * @throws \Exception
     */
    public static function  del($userid, $id)
    {
        Cache::delete('UserAddressNum' . $userid);// 删除缓存
        $status = static::find($id, ['address_status']);
        if ($status->address_status == 1) {
            DB::transaction(function () use ($id, $userid) {
                UserAddress::where(['address_id' => $id])->delete();
                $next = UserAddress::where(['address_user_id' => $userid])->first(['address_id']);
                if (!is_null($next)) {
                    UserAddress::where(['address_id' => $next->address_id])->update(['address_status' => 1]);
                }
            });
            return true;
        }
        return static::where(['address_id' => $id])->delete();
    }
}
