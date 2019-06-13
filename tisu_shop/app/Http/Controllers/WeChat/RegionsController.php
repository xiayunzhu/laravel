<?php

namespace App\Http\Controllers\WeChat;

use App\Handlers\RegionsHandler;
use App\Http\Controllers\Controller;
use App\Lib\Response\Result;
use Illuminate\Http\Request;

class RegionsController extends Controller
{

    /**
     * @var RegionsHandler
     */
    private $regionsHandler;

    public function __construct(RegionsHandler $regionsHandler)
    {
        $this->regionsHandler = $regionsHandler;
    }

    /**
     * @param Request $request
     * @param Result $result
     * @return array
     */
    public function  list(Request $request, Result $result)
    {
        $data = $this->regionsHandler->list($request);

        $result->succeed($data);

        return $result->toArray();

    }
}
