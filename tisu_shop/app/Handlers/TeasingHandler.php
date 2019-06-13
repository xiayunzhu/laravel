<?php
/**
 * Created by PhpStorm.
 * User: ML-06
 * Date: 2019/4/16
 * Time: 10:32
 */

namespace App\Handlers;

use App\Http\Requests\Api\Teasing\StoreRequest;
use App\Lib\Response\Result;
use App\Models\Teasing;
use App\Models\TeasingImg;
use Illuminate\Support\Facades\DB;

class TeasingHandler
{
    private $fields = ['title', 'content', 'user_id'];
    private $img = [];

    public function store(StoreRequest $request, int $user_id)
    {
        $teasing = DB::transaction(function () use ($request, $user_id) {
            $request->offsetSet('user_id', $user_id);
            $teasing = Teasing::create($request->only($this->fields));
            $img_url = $request->get('img') ? $request->get('img') : 0;
            if (is_array($img_url) > 0) {
                $teasing_img = [];
                for ($i = 0; $i < count($img_url); $i++) {
                    $teasing_img['img_url'] = $img_url[$i];
                    $teasing_img['teasing_id'] = $teasing->id;
                    TeasingImg::create($teasing_img);
                }

            }
            return true;
        }, 1);
        return $teasing;
    }

}