<?php

use Illuminate\Database\Seeder;

class SpecValuesTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     * php artisan db:seed --class=SpecValuesTableSeeder
     * @return void
     */
    public $spec_value=[
        '红色',
        '黄色',
        '大的',
        '小的',
        '厚的',
        '薄的',
        '重的',

    ];
    public function run()
    {

        //

        static $i=0;
        if (config('app.env') == 'production') {
            dd('生产环境, 不能执行');
            return;
        }

        $specs = \App\Models\Specs::orderBy('id', 'asc')->limit(10)->get();
        if ($specs) {
            foreach ($specs as $specs) {
                   foreach ($this->spec_value as $spec_value) {
                       \App\Models\SpecValues::create(['spec_id' => $specs->id, 'spec_value' => $spec_value]);
                       $i++;
                   }
            }
        }
        echo 'count:' . $i.PHP_EOL;
    }
}
