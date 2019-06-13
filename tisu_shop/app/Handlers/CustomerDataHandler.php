<?php
/**
 * Created by PhpStorm.
 * User: ML-06
 * Date: 2019/4/23
 * Time: 11:24
 */

namespace App\Handlers;


use App\Exceptions\BuyerCountException;
use App\Models\Buyer;
use App\Models\CustomerData;
use Illuminate\Http\Request;

class CustomerDataHandler
{

    public function timeStamp(string $param)
    {

        switch ($param) {
            case 'yesterday':
                $timestampStart = mktime(0, 0, 0, date('m'), date('d') - 1, date('Y'));
                $timestampEnd = mktime(0, 0, 0, date('m'), date('d'), date('Y')) - 1;
                break;
            case 'sevenDays':
                $timestampStart = mktime(0, 0, 0, date('m'), date('d') - 7, date('Y'));
                $timestampEnd = mktime(23, 59, 59, date('m'), date('d') - 1, date('Y'));
                break;
            case 'thirtyDays':
                $timestampStart = mktime(0, 0, 0, date('m') - 1, date('d'), date('Y'));
                $timestampEnd = mktime(23, 59, 59, date('m'), date('d') - 1, date('Y'));

                break;
            default:
                return [];
        }
        return ['start_time' => $timestampStart, 'end_time' => $timestampEnd];
    }

    public function yestTimeStampArray(string $param)
    {

        switch ($param) {
            case 'yesterday':
                $timestampStart = mktime(0, 0, 0, date('m'), date('d') - 2, date('Y'));
                $timestampEnd = mktime(0, 0, 0, date('m'), date('d') - 1, date('Y')) - 1;
                break;
            case 'sevenDays':
                $timestampStart = mktime(0, 0, 0, date('m'), date('d') - 14, date('Y'));
                $timestampEnd = mktime(23, 59, 59, date('m'), date('d') - 8, date('Y'));
                break;
            case 'thirtyDays':
                $timestampStart = mktime(0, 0, 0, date('m') - 2, date('d'), date('Y'));
                $timestampEnd = mktime(23, 59, 59, date('m') - 1, date('d') - 1, date('Y'));
                break;
            default:
                return [];
        }
        return ['start_time' => $timestampStart, 'end_time' => $timestampEnd];
    }


    public function data(Request $request)
    {
        $shop_id = $request->get('shop_id');
        $count = Buyer::where('shop_id', $shop_id)->count();
        $yestDay = $this->customer($this->timeStamp('yesterday'), $this->yestTimeStampArray('yesterday'), $shop_id);
        $seven = $this->customer($this->timeStamp('sevenDays'), $this->yestTimeStampArray('sevenDays'), $shop_id);
        $thirty = $this->customer($this->timeStamp('thirtyDays'), $this->yestTimeStampArray('thirtyDays'), $shop_id);
        $data[] = [
            'yestDay' => $yestDay,
            'seven' => $seven,
            'thirty' => $thirty,
            'count' => $count
        ];
        return $data;
    }

    public function customer(array $timeStampArray, array $yestTimeStampArray, int $shop_id)
    {
        $buyer = CustomerData::where('shop_id', $shop_id)->whereBetween('time', array_values($timeStampArray))->selectRaw('sum(customer_new) as customer_new')->selectRaw('sum(customer_day) as customer_day')->first()->toArray();
        $customer_new = isset($buyer['customer_new']) ? $buyer['customer_new'] : 0;
        $customer_day = isset($buyer['customer_day']) ? $buyer['customer_day'] : 0;
        $yest = CustomerData::where('shop_id', $shop_id)->whereBetween('time', array_values($yestTimeStampArray))->selectRaw('sum(customer_new) as customer_new')->first()->toArray();
        $yest_customer_new = isset($yest['customer_new']) ? $yest['customer_new'] : 0;
        $new = $customer_new - $yest_customer_new;
        $new = $new > 0 ? $new : 0;
        return ['new_customer' => $customer_new, 'daily_growth' => $new, 'all_customer' => $customer_day];
    }

    /**
     * @param Request $request
     * @return array
     */
    public function list(Request $request)
    {
        $shop_id = $request->get('shop_id');
        $TypeCountMap = [
            'man_count' => 0,
            'women_count' => 0,
            'count' => 0,
            'hasBuy_count' => 0,
            'un_bought' => 0,
        ];
        $list = Buyer::where('shop_id', $shop_id)->get(['gender', 'has_buy'])->toArray();
        if (count($list) == 0) {
            return $TypeCountMap;
        }
        array_walk($list, function ($value) use (&$TypeCountMap) {
            if ($value['gender'] == 1) {
                $TypeCountMap['man_count']++;
            } elseif ($value['gender'] == 2) {
                $TypeCountMap['women_count']++;
            } elseif ($value['gender'] == 0) {
                $TypeCountMap['count']++;
            }
            if ($value['has_buy'] == Buyer::HAS_BUY_YES) {
                $TypeCountMap['hasBuy_count']++;
            } elseif ($value['has_buy'] == Buyer::HAS_BUY_NO) {
                $TypeCountMap['un_bought']++;
            }
        });
        return $TypeCountMap;

    }

    public function update(Request $request, int $time, int $endTime)
    {

        $buyerCount = CustomerData::where('shop_id', $request->get('shop_id'))->whereBetween('time', [$time, $endTime])->first();
        if ($buyerCount) {
            $buyerCount->customer_day = $request->get('buyer_increase');
            $buyerCount->save();
            return $buyerCount;
        } else {
            throw new BuyerCountException('请输入一个有效时间');
        }
    }

}