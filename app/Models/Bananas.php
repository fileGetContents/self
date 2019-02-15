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
class Bananas extends Model
{
    use SoftDeletes;
    const FILES = ['banana_id', 'banana_img', 'banana_href', 'banana_time', 'banana_status'];
    protected $primaryKey = 'banana_id';
    protected $dates = ['deleted_at'];
    public $timestamps = false;


}
