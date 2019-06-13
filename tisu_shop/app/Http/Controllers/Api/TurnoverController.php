<?php
/**
 * Created by PhpStorm.
 * User: ML-06
 * Date: 2019/4/22
 * Time: 16:07
 */

namespace App\Http\Controllers\Api;


use App\Handlers\TurnoverHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Turnover\ListRequest;
use App\Http\Requests\Api\Turnover\MonthRequest;
use App\Lib\Response\Result;
use App\Models\TotalAssets;
use Illuminate\Http\Request;

class TurnoverController extends Controller
{
    /**
     * @var TurnoverHandler
     */
    private $total;
    private $turnHandler;

    /**
     * TurnoverController constructor.
     * @param TurnoverHandler $turnHandler
     */
    public function __construct(TurnoverHandler $turnHandler)
    {
        $this->turnHandler = $turnHandler;
    }

    /**
     * @param Request $request
     * @param Result $result
     * @return array
     */
    public function sum(Request $request, Result $result)
    {
        if ($request->get('shop_id')) {
            $asset = TotalAssets::where('shop_id', $request->get('shop_id'))->value('assets');
            $assets['total'] =!empty($asset) ? $asset : 0;
            $result->succeed($assets);
        } else {
            $result->failed('店铺ID不能为空');
        }
        return $result->toArray();
    }

    /**
     * @param Request $request
     * @param Result $result
     * @return array
     */
    public function info(Request $request, Result $result)
    {

        $shop_id = $request->get('shop_id');
        if ($shop_id) {
            $beginThismonth = mktime(0, 0, 0, date('m'), 1, date('Y'));
            $endThismonth = mktime(23, 59, 59, date('m'), date('t'), date('Y'));
            $list = $this->turnHandler->page($request, $beginThismonth, $endThismonth);
            $result->succeed($list);
        } else {
            $result->failed('店铺ID不能为空');
        }
        return $result->toArray();
    }

    /**
     * @param Request $request
     * @param Result $result
     * @return array
     */
    public function list(ListRequest $request, Result $result)
    {
        try {
            $begin_time = $request->get('begin_time');
            $end_time = $request->get('end_time');
            $beginToday = mktime(0, 0, 0, date('m', $begin_time), date('d', $begin_time), date('Y', $begin_time));
            $endToday = mktime(0, 0, 0, date('m', $end_time), date('d', $end_time) + 1, date('Y', $end_time)) - 1;
            $model = $this->turnHandler->page($request, $beginToday, $endToday);
            $result->succeed($model);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage());
        }
        return $result->toArray();
    }

    /**
     * @param MonthRequest $request
     * @param Result $result
     * @return array
     */
    public function monthList(MonthRequest $request, Result $result)
    {
        try {
            $month=strtotime(date('Y-m',$request->get('month')));
            $beginToday = mktime(0, 0, 0, date('m', $month), date('d', $month), date('Y', $month));
            $endToday = mktime(0, 0, 0, date('m', $month)+1, date('d', $month), date('Y', $month)) - 1;
            $model = $this->turnHandler->page($request, $beginToday, $endToday);
            $result->succeed($model);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage());
        }
        return $result->toArray();
    }
}