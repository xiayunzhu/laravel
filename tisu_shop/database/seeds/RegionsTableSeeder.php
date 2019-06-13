<?php

use Illuminate\Database\Seeder;

class RegionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * php artisan db:seed --class=RegionsTableSeeder
     * @return void
     */
    public function run()
    {
        //
        \DB::table('regions')->truncate();
        $file = storage_path('regions.txt');
        $str = file_get_contents($file);
        if ($str) {
            $arr = explode("\n", $str);
            $data = [];
            foreach ($arr as $item) {
                $rowArr = [];
                $row = explode("&", $item);
                foreach ($row as $v) {
                    list($key, $value) = explode("=", $v, 2);
                    $rowArr[$key] = trim($value);
                }
                $rowArr['created_at'] = \Carbon\Carbon::now();
                $rowArr['updated_at'] = \Carbon\Carbon::now();
                $data[] = $rowArr;
            }

            $res = \DB::table('regions')->insert($data);

            var_dump($res);
            echo PHP_EOL;
        } else {
            echo 'empty' . PHP_EOL;
        }


    }
}
