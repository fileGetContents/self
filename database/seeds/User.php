<?php

use Illuminate\Database\Seeder;
use App\Models\Users;

class User extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (range(1, 500) as $value) {
            Users::create([
                'user_img' => '/themes/img/user/user-logo-004.png'
            ]);
        }
    }
}
