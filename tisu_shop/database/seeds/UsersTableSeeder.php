<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     * php artisan db:seed --class=UsersTableSeeder
     * @return void
     */
    public function run()
    {

        //
        if (config('app.env') !== 'production') {
            $count = User::count();
            if ($count == 0) {
                factory(User::class)->create(['name' => 'admin', 'email' => 'admin@tisu.com', 'bool_admin' => 1]);
                factory(User::class)->create(['name' => 'JJG', 'email' => '378823123@qq.com', 'bool_admin' => 1]);

                $user = User::where('email', 'admin@tisu.com')->first();
                $user->assignRole('Admin');
                $user->save();

                $user = User::where('email', '378823123@qq.com')->first();
                // 初始化用户角色，将 1 号用户指派为『超级管理员』
                $user->assignRole('Admin');
                $user->save();

                factory(User::class)->create(['name' => 'JJG-ML', 'email' => '378823123-01@qq.com', 'phone' => '15869021866', 'user_type' => User::USER_TYPE_SELLER]);
                factory(User::class)->create(['name' => 'ZY-ML', 'email' => '378823123-01@qq.com', 'phone' => '15869021867', 'user_type' => User::USER_TYPE_SELLER]);

                echo 'insert: ' . __CLASS__ . ' end !' . PHP_EOL;
            } else {
                echo 'user count:' . $count . ' ship !' . PHP_EOL;
            }

        }
        echo 'run ' . __CLASS__ . ' end !' . PHP_EOL;

    }

}

