<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * php artisan db:seed --class=RolesTableSeeder
     * @return void
     */
    public function run()
    {
        //
        $rows = [
            ['name' => 'Operate', 'remarks' => '运营'],//PC
            ['name' => 'Seller', 'remarks' => '卖家'],//APP-手机登录
        ];
        foreach ($rows as $row) {
            $model = Role::where('name', $row['name'])->first();
            if (!$model) {
                $model = Role::create($row);
                echo print_r($model->toArray(), true);
            }
        }

        echo PHP_EOL . 'role create script end !';
    }
}
