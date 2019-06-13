<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;


class WelcomeController extends BaseController
{
    //
    public function dashboard()
    {
        return view('backend.dashboard');
    }

    /**
     * 没有访问权限-跳转页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function permissionDenied(){
        return view('backend.permission_denied');
    }
}
