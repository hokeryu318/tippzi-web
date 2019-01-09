<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;


class StageViewController extends BaseController
{
    public function stage1_previous()
    {
//        $res = $request->all();
//
//        $user_id = $res['user_id'];
//        var_dump($user_id);exit;
        return view('stage1');
    }

    public function stage2_previous()
    {

        return view('stage2');
    }

    public function stage3_previous()
    {

        return view('stage3');
    }
}

