<?php
/**
 * Created by PhpStorm.
 * User: ML-06
 * Date: 2019/5/11
 * Time: 11:48
 */

namespace App\Http\Controllers\Api;


use App\Handlers\EventHandler;
use App\Handlers\PromoHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Event\IdRequest;
use App\Lib\Response\Result;
use Illuminate\Http\Request;


class EventsController extends Controller
{
    private $eventHandler;
    private $promoHandler;

    /**
     * EventsController constructor.
     * @param EventHandler $eventHandler
     * @param PromoHandler $promoHandler
     */
    public function __construct(EventHandler $eventHandler, PromoHandler $promoHandler)
    {
        $this->eventHandler = $eventHandler;
        $this->promoHandler = $promoHandler;
    }

    /**
     * @param Request $request
     * @param Result $result
     * @return array
     */
    public function list(Request $request, Result $result)
    {
        try {
            $data = $this->eventHandler->page($request);
            if ($data)
                $result->succeed($data);

        } catch (\Exception $exception) {

            $result->failed($exception->getMessage(), $exception->getCode());
        }

        return $result->toArray();
    }

    /**
     * @param IdRequest $request
     * @param Result $result
     * @return array
     */
    public function detail(IdRequest $request, Result $result)
    {
        try {
            $data = $this->eventHandler->detail($request);
            if ($data)
                $result->succeed($data);

        } catch (\Exception $exception) {

            $result->failed($exception->getMessage(), $exception->getCode());
        }

        return $result->toArray();
    }
}