<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Validator;

class SectionProductsController extends Controller
{
    
    public function search(Request $request) {

        $validator = Validator::make($request->all(), [
            'section_id' => 'integer|min:0',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('section_products')
            ->select([
                'section_products.section_id as section_id',
                'products_product_id.id as id',
                'products_product_id.name as name',
                'products_product_id.desc as desc',
                'products_product_id.period as period',
                'products_product_id.teacher as teacher',
                'products_product_id.image as image',
                'products_product_id.price as price',
                'products_product_id.onsale as onsale',
                'products_product_id.is_auth as is_auth',
                'products_product_id.attr as attr',
                'products_product_id.cer_year as cer_year',
                'products_product_id.cer_industry as cer_industry',
                'products_product_id.is_project as is_project',
            ]);
        $result = $result->leftJoin('products as products_product_id', 'products_product_id.id', '=', 'section_products.product_id');
        if ($request->has('section_id'))
            $result = $result->where('section_products.section_id', '=', $request->input('section_id'));
        $result = $result->get();
        return $this->success($result);
    }
    public function create(Request $request) 
    {
        $result = \App\Utils\SectionProductsUtil::create($request);
        return $this->success($result);
    }
    public function delete(Request $request) {

        $validator = Validator::make($request->all(), [
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('section_products');
        if ($request->has('product_id'))
            $result = $result->where('section_products.product_id', '=', $request->input('product_id'));
        if ($request->has('section_id'))
            $result = $result->where('section_products.section_id', '=', $request->input('section_id'));
        $result->delete();
        return $this->success();
    }
}
