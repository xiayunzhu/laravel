<?php
/**
 * Created by PhpStorm.
 * User: JJG
 * Date: 2019/4/22
 * Time: 20:31
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Lib\Response\Result;

class BaseController extends Controller
{
    /**
     * @var Result
     */
    protected $result;

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->result = app(Result::class);
    }
}