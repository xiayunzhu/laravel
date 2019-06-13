<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * php artisan db:seed --class=PermissionsTableSeeder
     * @return void
     */
    public function run()
    {
        //
        // Reset cached roles and permissions
        app()['cache']->forget('spatie.permission.cache');

        //
        $rows = [
            ['name' => 'manage_system', 'remarks' => '系统设置'],
            ['name' => 'manage_users', 'remarks' => '用户管理'],
            ['name' => 'can_export_user', 'remarks' => '用户导出'],
            ['name' => 'manage_permissions', 'remarks' => '权限管理'],
            ['name' => 'manage_roles', 'remarks' => '角色管理'],
            ['name' => 'manage_menu_other', 'remarks' => '其他功能'],
            ['name' => 'manage_menu_logs', 'remarks' => '系统日志'],
            ['name' => 'manage_menu_base_info', 'remarks' => '基础资料'],
            ['name' => 'manage_menu_shop_info', 'remarks' => '店铺资料'],
            ['name' => 'manage_menu_region_info', 'remarks' => '区域管理'],
            ['name' => 'manage_menu_wxapp_info', 'remarks' => '微信小程序'],
            ['name' => 'manage_menu_orgGood_info', 'remarks' => '原始商品'],
        ];
        foreach ($rows as $row) {
            $model = Permission::where('name', $row['name'])->first();
            if (!$model) {
                $model = Permission::create($row);
                echo print_r($model->toArray(), true);
            }
//            else {
//
//                echo 'exist:' . var_export($row, true) . PHP_EOL;
//            }
        }
    }
}
