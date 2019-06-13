<?php

use Illuminate\Database\Seeder;

class BrandsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * php artisan db:seed --class=BrandsTableSeeder
     * @return void
     */
    public function run()
    {
        //
        if (config('app.env') == 'production') {
            dd('生产环境, 不能执行');
            return;

        }

        $brands = [
            ['name' => '路易威登', 'country' => '法国'],
            ['name' => '爱马仕', 'country' => '法国'],
            ['name' => '古驰', 'country' => '意大利'],
            ['name' => '香奈儿', 'country' => '法国'],
            ['name' => '轩尼诗', 'country' => '法国'],
            ['name' => '劳力士', 'country' => '瑞士'],
            ['name' => '酩悦香槟', 'country' => '法国'],
            ['name' => '卡地亚', 'country' => '法国'],
            ['name' => '芬迪', 'country' => '美国'],
            ['name' => '蒂芙尼', 'country' => '美国'],
        ];

        foreach ($brands as $brand) {
            factory(\App\Models\Brand::class, 1)->create($brand);
        }

        echo PHP_EOL;
    }
}
