<?php

use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * php artisan db:seed --class=CategoriesTableSeeder
     * @return void
     */
    public function run()
    {
        if (config('app.env') == 'production') {
            dd('生产环境, 不能执行');
            return;

        }

        $names = ['男装', '女装', '口红', '裙子'];
        foreach ($names as $name) {
            $model = factory(\App\Models\Category::class, 1)->create(['name' => $name]);
        }
    }
}
