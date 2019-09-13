<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Validator;

class PolyvController extends Controller
{
    public function upload_call_back(Request $request) 
    {
        $result = \App\Utils\PolyvUtil::polyv_call_back($request);
        return $this->success($result);
    }
}
