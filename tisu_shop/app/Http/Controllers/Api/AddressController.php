<?php

namespace App\Http\Controllers\Api;

use App\Handlers\DeliveryRulesHandler;
use App\Models\BuyerAddress;
use App\Models\Region;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Ml\Response\Result;
use Symfony\Component\Routing\Router;

class AddressController extends Controller
{
    private $AddressHandler;

    public function __construct(DeliveryRulesHandler $addressHandler)
    {
        $this->AddressHandler = $addressHandler;
    }

    /*
     * list
     * */
    public  function  lists(Result $result){
        $res=Region::where('pid',0)->get();

        $province=\request('province');
//        return response()->json(['data'=>$province]);
        $city=\request('city');
        $country=\request('country');
            if($province!=null) {
//                $res = Region::where('id', $province)->first();
                    $res = Region::from('regions as r1')
                        ->leftJoin('regions as r2', 'r1.id', '=', 'r2.pid')
                        ->where('r1.id', $province)
                        ->select('r1.pid', 'r2.id', 'r2.shortname', 'r2.name')
                        ->get();
            }
            if ($city != null) {
                $res = Region::from('regions as r1')
                    ->leftJoin('regions as r2', 'r1.id', '=', 'r2.pid')
                    ->where('r1.id',$city)
                    ->select('r1.pid','r2.id','r2.shortname','r2.name')
                    ->get();
                }
            if($country!=null){
                $result->failed('没有更多数据！');
                return response()->json($result->toArray());
            }
//            $res = $this->DeliveryRulesHandler->page(\request());
            $result->succeed($res);
        return response()->json($result->toArray());

    }

}
