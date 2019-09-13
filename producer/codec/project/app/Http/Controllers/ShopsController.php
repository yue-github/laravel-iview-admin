<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Validator;

class ShopsController extends Controller
{
    
    public function get(Request $request) {

        $validator = Validator::make($request->all(), [
            'url' => 'string',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('shops')
            ->select([
                'shops.id as id',
                'shops.name as name',
                'shops.owner_id as owner_id',
                'shops.state as state',
                'shops.is_project as is_project',
                'shops.platform_name as platform_name',
                'shops.mechanism_name as mechanism_name',
                'shops.region as region',
                'shops.is_invoice as is_invoice',
                'shops.agreement as agreement',
                'shops.is_cer as is_cer',
                'shops.logo_header_file_name as logo_header_file_name',
                'shops.logo_footer_file_name as logo_footer_file_name',
                'shops.customer2 as customer2',
                'shops.customer as customer',
                'shops.footer as footer',
                'shops.alipay as alipay',
                'shops.wxpay as wxpay',
                'shops.unionpay as unionpay',
                'shops.is_auth as is_auth',
                'shops.marquee as marquee',
                'shops.title as title',
                'shops.sidebar_type as sidebar_type',
                'shops.is_order as is_order',
                'shops.is_shoppingcart as is_shoppingcart',
            ]);
        if ($request->has('url'))
            $result = $result->where('shops.url', '=', $request->input('url'));
        $result = $result->first();
        return $this->success($result);
    }
    
    public function search(Request $request) {

        $validator = Validator::make($request->all(), [
            'offset' => 'required|integer|min:0',
            'length' => 'required|integer|min:1',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('shops')
            ->select([
                'shops.name as name',
                'shops.state as state',
            ]);
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
    public function create(Request $request) {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'state' => 'required|integer',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        DB::table('shops')->insert([
            'name' => $request->input('name'),
            'state' => $request->input('state'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        return $this->success();
    }
    public function shop_create(Request $request) {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'owner_id' => 'required|integer|min:0',
            'state' => 'required|integer',
            'is_project' => 'integer',
            'platform_name' => 'string|max:100',
            'mechanism_name' => 'string|max:100',
            'region' => 'string|max:100',
            'is_invoice' => 'integer',
            'agreement' => 'string',
            'is_cer' => 'integer',
            'logo_header_file_name' => 'string|max:100',
            'logo_footer_file_name' => 'string|max:100',
            'customer2' => 'string',
            'customer' => 'string',
            'footer' => 'string',
            'alipay' => 'string',
            'wxpay' => 'string',
            'unionpay' => 'string',
            'is_auth' => 'integer',
            'marquee' => 'string',
            'title' => 'string|max:100',
            'sidebar_type' => 'integer',
            'is_order' => 'integer',
            'is_shoppingcart' => 'integer',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        DB::table('shops')->insert([
            'name' => $request->input('name'),
            'owner_id' => $request->input('owner_id'),
            'state' => $request->input('state'),
            'is_project' => $request->input('is_project'),
            'platform_name' => $request->input('platform_name'),
            'mechanism_name' => $request->input('mechanism_name'),
            'region' => $request->input('region'),
            'is_invoice' => $request->input('is_invoice'),
            'agreement' => $request->input('agreement'),
            'is_cer' => $request->input('is_cer'),
            'logo_header_file_name' => $request->input('logo_header_file_name'),
            'logo_footer_file_name' => $request->input('logo_footer_file_name'),
            'customer2' => $request->input('customer2'),
            'customer' => $request->input('customer'),
            'footer' => $request->input('footer'),
            'alipay' => $request->input('alipay'),
            'wxpay' => $request->input('wxpay'),
            'unionpay' => $request->input('unionpay'),
            'is_auth' => $request->input('is_auth'),
            'marquee' => $request->input('marquee'),
            'title' => $request->input('title'),
            'sidebar_type' => $request->input('sidebar_type'),
            'is_order' => $request->input('is_order'),
            'is_shoppingcart' => $request->input('is_shoppingcart'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
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

        $result = DB::table('shops')
            ->select([
                'shops.id as id',
                'shops.name as name',
                'users_owner_id.name as user_name',
                'users_owner_id.company as user_company',
                'users_owner_id.phone as user_phone',
                'shops.is_project as is_project',
                'shops.url as url',
                'shops.platform_name as platform_name',
                'shops.mechanism_name as mechanism_name',
                'shops.region as region',
                'shops.is_invoice as is_invoice',
                'shops.agreement as agreement',
                'shops.is_cer as is_cer',
                'shops.logo_header_file_name as logo_header_file_name',
                'shops.logo_footer_file_name as logo_footer_file_name',
                'shops.customer2 as customer2',
                'shops.customer as customer',
                'shops.footer as footer',
                'shops.alipay as alipay',
                'shops.wxpay as wxpay',
                'shops.unionpay as unionpay',
                'shops.is_auth as is_auth',
                'shops.marquee as marquee',
                'shops.title as title',
                'shops.sidebar_type as sidebar_type',
                'shops.is_order as is_order',
                'shops.is_shoppingcart as is_shoppingcart',
            ]);
        $result = $result->leftJoin('users as users_owner_id', 'users_owner_id.id', '=', 'shops.owner_id');
        if ($request->has('name'))
            $result = $result->where('shops.name', 'like', '%'.$request->input('name').'%');
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
            'name' => 'required|string|max:100',
            'owner_id' => 'required|integer|min:0',
            'state' => 'required|integer',
            'is_project' => 'integer',
            'platform_name' => 'string|max:100',
            'mechanism_name' => 'string|max:100',
            'region' => 'string|max:100',
            'is_invoice' => 'integer',
            'agreement' => 'string',
            'is_cer' => 'integer',
            'logo_header_file_name' => 'string|max:100',
            'logo_footer_file_name' => 'string|max:100',
            'customer2' => 'string',
            'customer' => 'string',
            'footer' => 'string',
            'alipay' => 'string',
            'wxpay' => 'string',
            'unionpay' => 'string',
            'is_auth' => 'integer',
            'marquee' => 'string',
            'title' => 'string|max:100',
            'sidebar_type' => 'integer',
            'is_order' => 'integer',
            'is_shoppingcart' => 'integer',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('shops');
        $info = 'shopsController->shop_edit: ';
        if ($request->has('id'))
            $result = $result->where('shops.id', '=', $request->input('id'));
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
        if ($request->has('owner_id')){
            $data["owner_id"] = $request->input('owner_id');
            $info = $info . 'owner_id => ' . $request->input('owner_id') . ', ';
        }
        if ($request->has('state')){
            $data["state"] = $request->input('state');
            $info = $info . 'state => ' . $request->input('state') . ', ';
        }
        if ($request->has('is_project')){
            $data["is_project"] = $request->input('is_project');
            $info = $info . 'is_project => ' . $request->input('is_project') . ', ';
        }
        if ($request->has('platform_name')){
            $data["platform_name"] = $request->input('platform_name');
            $info = $info . 'platform_name => ' . $request->input('platform_name') . ', ';
        }
        if ($request->has('mechanism_name')){
            $data["mechanism_name"] = $request->input('mechanism_name');
            $info = $info . 'mechanism_name => ' . $request->input('mechanism_name') . ', ';
        }
        if ($request->has('region')){
            $data["region"] = $request->input('region');
            $info = $info . 'region => ' . $request->input('region') . ', ';
        }
        if ($request->has('is_invoice')){
            $data["is_invoice"] = $request->input('is_invoice');
            $info = $info . 'is_invoice => ' . $request->input('is_invoice') . ', ';
        }
        if ($request->has('agreement')){
            $data["agreement"] = $request->input('agreement');
            $info = $info . 'agreement => ' . $request->input('agreement') . ', ';
        }
        if ($request->has('is_cer')){
            $data["is_cer"] = $request->input('is_cer');
            $info = $info . 'is_cer => ' . $request->input('is_cer') . ', ';
        }
        if ($request->has('logo_header_file_name')){
            $data["logo_header_file_name"] = $request->input('logo_header_file_name');
            $info = $info . 'logo_header_file_name => ' . $request->input('logo_header_file_name') . ', ';
        }
        if ($request->has('logo_footer_file_name')){
            $data["logo_footer_file_name"] = $request->input('logo_footer_file_name');
            $info = $info . 'logo_footer_file_name => ' . $request->input('logo_footer_file_name') . ', ';
        }
        if ($request->has('customer2')){
            $data["customer2"] = $request->input('customer2');
            $info = $info . 'customer2 => ' . $request->input('customer2') . ', ';
        }
        if ($request->has('customer')){
            $data["customer"] = $request->input('customer');
            $info = $info . 'customer => ' . $request->input('customer') . ', ';
        }
        if ($request->has('footer')){
            $data["footer"] = $request->input('footer');
            $info = $info . 'footer => ' . $request->input('footer') . ', ';
        }
        if ($request->has('alipay')){
            $data["alipay"] = $request->input('alipay');
            $info = $info . 'alipay => ' . $request->input('alipay') . ', ';
        }
        if ($request->has('wxpay')){
            $data["wxpay"] = $request->input('wxpay');
            $info = $info . 'wxpay => ' . $request->input('wxpay') . ', ';
        }
        if ($request->has('unionpay')){
            $data["unionpay"] = $request->input('unionpay');
            $info = $info . 'unionpay => ' . $request->input('unionpay') . ', ';
        }
        if ($request->has('is_auth')){
            $data["is_auth"] = $request->input('is_auth');
            $info = $info . 'is_auth => ' . $request->input('is_auth') . ', ';
        }
        if ($request->has('marquee')){
            $data["marquee"] = $request->input('marquee');
            $info = $info . 'marquee => ' . $request->input('marquee') . ', ';
        }
        if ($request->has('title')){
            $data["title"] = $request->input('title');
            $info = $info . 'title => ' . $request->input('title') . ', ';
        }
        if ($request->has('sidebar_type')){
            $data["sidebar_type"] = $request->input('sidebar_type');
            $info = $info . 'sidebar_type => ' . $request->input('sidebar_type') . ', ';
        }
        if ($request->has('is_order')){
            $data["is_order"] = $request->input('is_order');
            $info = $info . 'is_order => ' . $request->input('is_order') . ', ';
        }
        if ($request->has('is_shoppingcart')){
            $data["is_shoppingcart"] = $request->input('is_shoppingcart');
            $info = $info . 'is_shoppingcart => ' . $request->input('is_shoppingcart') . ', ';
        }
        $data["updated_at"] = date('Y-m-d H:i:s');
        $info = $info . 'updated_at => ' . date('Y-m-d H:i:s') . ', ';
        $info = $info . "}";
        Log::info($info);
        $result->update($data);
        return $this->success();
    }
    public function shop_delete(Request $request) {

        $validator = Validator::make($request->all(), [
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('shops');
        if ($request->has('id'))
            $result = $result->where('shops.id', '=', $request->input('id'));
        $result->delete();
        return $this->success();
    }
    
    public function get_url(Request $request) {

        $validator = Validator::make($request->all(), [
            'url' => 'string',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('shops')
            ->select([
                'shops.id as id',
                'shops.name as name',
                'shops.is_shoppingcart as is_shoppingcart',
            ]);
        if ($request->has('url'))
            $result = $result->where('shops.url', '=', $request->input('url'));
        $result = $result->first();
        return $this->success($result);
    }
    
    public function get_shopid(Request $request) {

        $validator = Validator::make($request->all(), [
            'id' => 'integer|min:0',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('shops')
            ->select([
                'shops.owner_id as owner_id',
                'shops.name as name',
                'shops.platform_name as platform_name',
                'shops.is_shoppingcart as is_shoppingcart',
            ]);
        if ($request->has('id'))
            $result = $result->where('shops.id', '=', $request->input('id'));
        $result = $result->first();
        return $this->success($result);
    }
}
