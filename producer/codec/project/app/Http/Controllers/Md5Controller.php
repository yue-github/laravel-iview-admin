<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Validator;

class Md5Controller extends Controller
{
    public function get(Request $request) 
    {
        $result = \App\Utils\PolyvUtil::get_md5($request);
        return $this->success($result);
    }
}
