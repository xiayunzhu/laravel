<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * php artisan db:seed
     * @return void
     */
    public function run()
    {
        $this->call(PermissionsTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(RegionsTableSeeder::class);

        if (config('app.env') != 'production') {
            //测试环境
            $this->call(ShopTableSeeder::class);
            $this->call(WxappsTableSeeder::class);
            $this->call(BuyersTableSeeder::class);
            $this->call(BuyerAddressesTableSeeder::class);
            $this->call(SpecsTableSeeder::class);
            $this->call(SpecValuesTableSeeder::class);
            $this->call(RegionsTableSeeder::class);
            $this->call(BrandsTableSeeder::class);
            $this->call(CategoriesTableSeeder::class);
            $this->call(PageContentsTableSeeder::class);
            $this->call(GoodsTableSeeder::class);
            $this->call(DeliveryRulesTableSeeder::class);
            $this->call(OrdersTableSeeder::class);
            $this->call(PromosTableSeeder::class);
        }
    }
}
