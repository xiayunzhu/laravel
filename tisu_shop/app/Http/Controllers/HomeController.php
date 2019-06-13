<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    /**
     * 测试1
     * @param Request $request
     * @return string
     */
    public function test(Request $request)
    {
        return view('test');#在你的视图文件夹创建test.blade.php
    }


    /**
     * 测试2
     * @param Request $request
     * @return string
     */
    public function test2(Request $request)
    {
        return 'Hello World2:' . $request->get('name');
    }
}
