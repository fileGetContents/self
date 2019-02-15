<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Passport\HasApiTokens;
/**
 * App\User
 *
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User query()
 * @mixin \Eloquent
 */
class User extends Model
{
    use SoftDeletes,HasApiTokens;

    protected $primaryKey = 'user_id';
    public $dates = ['deleted_at'];
    const FILES = ['user_id', 'user_img', 'user_email', 'user_password', 'user_time', 'user_status'];
    public $timestamps = false;
    public $fillable = ['user_img', 'user_email', 'user_password', 'user_time', 'user_status'];


    public static function seedActivationEmail($email)
    {
        encrypt($email);
    }


}
