<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

/**
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Goods newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Goods newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Goods query()
 * @mixin \Eloquent
 */
class Groups extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'group_id';
    const FILES = ['group_id', 'group_user_id', 'group_good_id', 'group_users', 'group_time', 'group_end_time', 'group_number'];
    protected $dates = ['deleted_at'];
    public $timestamps = false;

    /**
     * 查询是否可以创建一个
     * @param $goodId
     * @return 查询是否可以创建一个
     */
    public static function isGroupByGood($goodId, $userId)
    {
        $name = 'isGroupByGood' . $goodId . 'u' . $userId;
        $id = Cache::remember($name, 10, function () use ($goodId, $userId) {
            return static::select('group_id')
                ->where(['group_status' => 0, 'group_pay' => 1, 'group_good_id' => $goodId, 'group_user_id' => $userId])
                ->first();
        });
        if (is_null($id)) {
            return true;
        }
        return false;
    }

    /**
     * @param $id
     * @return 获取商品分组[5组]
     */
    public static function getGroupByGoodId($id)
    {
        return static::where(['group_good_id' => $id, 'group_status' => 0, 'group_pay' => 1])
            ->where('group_number', '>', 0)
            ->limit(5)
            ->orderBy('group_number', 'asc')
            ->join('users', 'users.user_id', '=', 'groups.group_user_id')
            ->select(['user_img', 'group_id', 'group_end_time', 'group_number'])
            ->get();
    }


    public function getGroupEndTimeAttribute($v)
    {
        return date('Y-m-d H:i:s', $v);
    }

}
