<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Validator;

class SectionsController extends Controller
{
    
    public function search(Request $request) {

        $validator = Validator::make($request->all(), [
            'offset' => 'required|integer|min:0',
            'length' => 'required|integer|min:1',
            'shop_id' => 'integer|min:0',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('sections')
            ->select([
                'sections.id as id',
                'sections.shop_id as shop_id',
                'sections.page as page',
                'sections.template as template',
                'sections.name as name',
                'sections.sort as sort',
            ]);
        if ($request->has('shop_id'))
            $result = $result->where('sections.shop_id', '=', $request->input('shop_id'));
        $result = $result->orderBy('sections.sort', 'asc');
        $count = $result->count();
        $result = $result
            ->offset($request->input('offset'))
            ->limit($request->input('length'))
            ->get();
        
        for($result_i = 0; $result_i < count($result); $result_i++) {
            $section_products = DB::table('section_products')->select([                'products_product_id.id as product_id',
                'products_product_id.name as product_name',
                'products_product_id.desc as product_desc',
                'products_product_id.period as product_period',
                'products_product_id.teacher as product_teacher',
                'products_product_id.image as product_image',
                'products_product_id.price as product_price',
                'products_product_id.onsale as product_onsale',
                'products_product_id.is_auth as product_is_auth',
                'products_product_id.attr as product_attr',
                'products_product_id.cer_year as product_cer_year',
                'products_product_id.cer_industry as product_cer_industry',
                'products_product_id.is_project as product_is_project',
            ]);
        $section_products = $section_products->leftJoin('products as products_product_id', 'products_product_id.id', '=', 'section_products.product_id');
        $section_products = $section_products->where('section_id', $result[$result_i]->id);
        $section_products = $section_products->get();
        $result[$result_i]->section_products = $section_products;
        }
        for($result_i = 0; $result_i < count($result); $result_i++) {
            $package_section = DB::table('package_section')->select([                'package_package_id.id as package_id',
                'package_package_id.name as name',
                'package_package_id.title1 as title1',
                'package_package_id.title2 as title2',
                'package_package_id.img as img',
            ]);
        $package_section = $package_section->leftJoin('package as package_package_id', 'package_package_id.id', '=', 'package_section.package_id');
        $package_section = $package_section->where('section_id', $result[$result_i]->id);
        $package_section = $package_section->get();
        $result[$result_i]->package_section = $package_section;
        for($package_section_i = 0; $package_section_i < count($package_section); $package_section_i++) {
            $package_product = DB::table('package_product')->select([                'products_product_id.name as product_name',
                'products_product_id.year as product_year',
                'projects_project_id.start_stydy_time as start_stydy_time',
                'projects_project_id.end_study_time as end_study_time',
            ]);
        $package_product = $package_product->leftJoin('products as products_product_id', 'products_product_id.id', '=', 'package_product.product_id');
        $package_product = $package_product->leftJoin('projects as projects_project_id', 'projects_project_id.id', '=', 'products_product_id.project_id');
        $package_product = $package_product->where('package_id', $package_section[$package_section_i]->package_id);
        $package_product = $package_product->get();
        $package_section[$package_section_i]->package_product = $package_product;
        }
        }
        $result = [
            'data' => $result,
            'total' => $count
        ];
        return $this->success($result);
    }
    
    public function search_package(Request $request) {

        $validator = Validator::make($request->all(), [
            'offset' => 'required|integer|min:0',
            'length' => 'required|integer|min:1',
            'shop_id' => 'integer|min:0',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('sections')
            ->select([
                'sections.id as id',
            ]);
        if ($request->has('shop_id'))
            $result = $result->where('sections.shop_id', '=', $request->input('shop_id'));
        $result = $result->orderBy('sections.sort', 'asc');
        $count = $result->count();
        $result = $result
            ->offset($request->input('offset'))
            ->limit($request->input('length'))
            ->get();
        
        for($result_i = 0; $result_i < count($result); $result_i++) {
            $package_section = DB::table('package_section')->select([                'package_package_id.name as name',
                'package_package_id.address as address',
                'package_package_id.title1 as title1',
                'package_package_id.title2 as title2',
                'package_package_id.year as year',
            ]);
        $package_section = $package_section->leftJoin('package as package_package_id', 'package_package_id.id', '=', 'package_section.package_id');
        $package_section = $package_section->where('section_id', $result[$result_i]->id);
        $package_section = $package_section->get();
        $result[$result_i]->package_section = $package_section;
        }
        $result = [
            'data' => $result,
            'total' => $count
        ];
        return $this->success($result);
    }
    public function create(Request $request) {

        $validator = Validator::make($request->all(), [
            'shop_id' => 'integer|min:0',
            'name' => 'string|max:100',
            'desc' => 'string|max:100',
            'sort' => 'integer',
            'template' => 'integer',
            'page' => 'integer',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        DB::table('sections')->insert([
            'shop_id' => $request->input('shop_id'),
            'name' => $request->input('name'),
            'desc' => $request->input('desc'),
            'sort' => $request->input('sort'),
            'template' => $request->input('template'),
            'page' => $request->input('page'),
        ]);
        return $this->success();
    }
    
    public function shop_search(Request $request) {

        $validator = Validator::make($request->all(), [
            'offset' => 'required|integer|min:0',
            'length' => 'required|integer|min:1',
            'name' => 'string|max:100',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('sections')
            ->select([
                'sections.id as id',
                'sections.shop_id as shop_id',
                'sections.page as page',
                'sections.desc as desc',
                'sections.template as template',
                'sections.name as name',
                'sections.sort as sort',
            ]);
        $result = $result->where('sections.shop_id', '=', $this->token->shop_id);
        if ($request->has('name'))
            $result = $result->where('sections.name', 'like', '%'.$request->input('name').'%');
        $result = $result->orderBy('sections.sort', 'asc');
        $count = $result->count();
        $result = $result
            ->offset($request->input('offset'))
            ->limit($request->input('length'))
            ->get();
        
        $result = [
            'data' => $result,
            'total' => $count
        ];
        return $this->success($result);
    }
    public function shop_edit(Request $request) {

        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|min:0',
            'name' => 'string|max:100',
            'desc' => 'string|max:100',
            'sort' => 'integer',
            'template' => 'integer',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('sections');
        $info = 'sectionsController->shop_edit: ';
        if ($request->has('id'))
            $result = $result->where('sections.id', '=', $request->input('id'));
        $info = $info . 'with: {'; 
        if ($request->has('id')) 
            $info = $info . 'id => ' . $request->input('id') . ', '; 
        $info = $info . 'id => ' . $request->input('id') . ', ';
        $info = $info . "}, ";
        $data =[];
        $info = $info . 'data: {';
        if ($request->has('name')){
            $data["name"] = $request->input('name');
            $info = $info . 'name => ' . $request->input('name') . ', ';
        }
        if ($request->has('desc')){
            $data["desc"] = $request->input('desc');
            $info = $info . 'desc => ' . $request->input('desc') . ', ';
        }
        if ($request->has('sort')){
            $data["sort"] = $request->input('sort');
            $info = $info . 'sort => ' . $request->input('sort') . ', ';
        }
        if ($request->has('template')){
            $data["template"] = $request->input('template');
            $info = $info . 'template => ' . $request->input('template') . ', ';
        }
        $info = $info . "}";
        Log::info($info);
        $result->update($data);
        return $this->success();
    }
    
    public function shop_get(Request $request) {

        $validator = Validator::make($request->all(), [
            'id' => 'integer|min:0',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('sections')
            ->select([
                'sections.name as name',
                'sections.sort as sort',
                'sections.template as template',
                'sections.page as page',
            ]);
        if ($request->has('id'))
            $result = $result->where('sections.id', '=', $request->input('id'));
        $result = $result->first();
        return $this->success($result);
    }
    public function shop_delete(Request $request) {

        $validator = Validator::make($request->all(), [
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('sections');
        if ($request->has('id'))
            $result = $result->where('sections.id', '=', $request->input('id'));
        $result->delete();
        return $this->success();
    }
}
