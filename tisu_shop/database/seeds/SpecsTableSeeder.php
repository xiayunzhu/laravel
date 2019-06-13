<?php
/**
 * Run the database seeds.
 * php artisan db:seed --class=SpecsTableSeeder
 * @return void
 */

use App\Models\Specs;
use Illuminate\Database\Seeder;

class SpecsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * php artisan db:seed --class=SpecsTableSeeder
     * @return void
     */
    protected $specs = [
        '品牌',
        '颜色',
        '材料',
        '版型',
        '厚度',
        '触感',
        '弹性',

    ];

    public function run()
    {
        if (config('app.env') == 'production') {
            dd('生产环境, 不能执行');
            return;

        }

        static $i = 0;
        foreach ($this->specs as $spec) {
            Specs::create(['spec_name' => $spec,]);

            $i++;
        }
        echo 'i:' . $i . PHP_EOL;
    }
}
