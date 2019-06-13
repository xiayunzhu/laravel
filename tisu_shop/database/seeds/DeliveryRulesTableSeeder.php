<?php

use Illuminate\Database\Seeder;

class DeliveryRulesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * php artisan db:seed --class=DeliveryRulesTableSeeder
     * @return void
     */
    public function run()
    {
        if (config('app.env') == 'production') {
            dd('生产环境, 不能执行');
            return;

        }
        //
        /*运费模板
         * */
        $deliveries_data = [
            'name' => '模板2',                                     //运费模板名称
            'method' => \App\Models\Deliveries::METHOD_PIECE,        //计费方式
            'sort' => '22',
//            'created_at' => time(),
//            'updated_at' => time()

        ];
        $delivery_id = \App\Models\Deliveries::insertGetId($deliveries_data);
        if ($delivery_id) {
            $row = [
                'delivery_id' => $delivery_id,                       //配送规则id
                'region' => "2,3,10,10,200,210,201",                                  //配送地址
                'first' => 1,                                         //首重 or 首件
                'first_fee' => 10,                                   //首重费用 or 首件费用
                'additional' => 1,                                    //续重   or 续件
                'additional_fee' => 20,                                //续重费用 or 续件费用
                'created_at' => time(),
                'updated_at' => time()

            ];
            $res = \App\Models\DeliveryRule::create($row);
            if ($res) {
                echo 'true';
            } else {
                echo 'delivery_rules is empty';
            }

        } else {
            echo 'deliveries is empty';


        }

    }
}
