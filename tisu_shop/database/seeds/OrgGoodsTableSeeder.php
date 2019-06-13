<?php

use Illuminate\Database\Seeder;
use App\Models\OrgGood;



class OrgGoodsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * php artisan db:seed --class=CategoriesTableSeeder
     * @return void
     */
    public function run()
    {
//        if (config('app.env') == 'production') {
//            dd('生产环境, 不能执行');
//            return;
//
//        }
        factory(OrgGood::class)->create();

    }
}
