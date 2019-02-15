<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Goods newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Goods newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Goods query()
 * @mixin \Eloquent
 */
class Goods extends Model
{
    use SoftDeletes;
    const FILE = ['good_id', 'good_title', 'good_info', 'good_images', 'good_image', 'good_price', 'good_market_price', 'good_end_time', 'good_participation', 'good_people', 'good_text', 'good_time', 'good_status', 'good_place', 'good_fare', 'good_invoice'];
    protected $primaryKey = 'good_id';
    protected $fillable = ['good_title', 'good_info', 'good_images', 'good_image', 'good_price', 'good_market_price', 'good_end_time', 'good_participation', 'good_people', 'good_text', 'good_time', 'good_status', 'good_place'];
    protected $dates = ['deleted_at'];
    public $timestamps = false;

    /**
     *
     * @param $id
     * @return 获取商品基本信息
     */
    public static function getBaseById($id)
    {
        return static::where(['good_id' => $id, 'good_status' => 1])->first(static::FILE);
    }


    /**
     * 获取图片集
     * @param $value
     * @return mixed
     */
    public function getGoodImagesAttribute($value)
    {
        return unserialize($value);
    }

    /**
     * 获取参团人数
     * @param $value
     * @return string
     */
    public function getGoodParticipationAttribute($value)
    {
        if ($value < 1000) {
            return $value;
        }
        return sprintf("%.2f", $value / 1000) . 'K';
    }

    /**
     * 限制过期商品
     * @param $query
     * @return mixed
     */
    public function scopeEndTime($query)
    {
        return $query->where('good_end_time', '>=', $_SERVER['REQUEST_TIME']);
    }


}
