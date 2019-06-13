<?php
/**
 * Created by PhpStorm.
 * User: ML-06
 * Date: 2019/4/22
 * Time: 12:04
 */

namespace App\Handlers;


use App\Exceptions\AssetException;
use App\Models\TotalAssets;
use App\Models\Turnover;

class AssetsHandler
{
    public function store(array $turnover)
    {
        $model = \DB::transaction(function () use ($turnover) {
            $model = Turnover::create($turnover);
            $total_asset = TotalAssets::where('shop_id',$turnover['shop_id'])->first();

            if (empty($total_asset)) {
                $total_asset = new TotalAssets();
                $total_asset->shop_id=$turnover['shop_id'];
                $total_asset->assets = $turnover['payment'];
                $total_asset->save();
            } else {
                $total_asset->assets = $total_asset->assets + $turnover['payment'];
                $total_asset->save();
            }


            return $model;

        });
        return $model;
    }

}