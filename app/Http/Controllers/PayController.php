<?php 
 
namespace App\Http\Controllers;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
// 支付
use EasyWeChat\Factory;
use App\Repositories\WxPayRepository;
use function EasyWeChat\Kernel\Support\generate_sign;

// use Illuminate\Http\Response;
class PayController extends Controller{
    public function init(){
         date_default_timezone_set('PRC');
    }
    public function wxpay(){
        // $code=request('code');
        // // 支付没必要将code带入获取openid,因为在登录时已经得到了openid前端携带就行
        // $mini = \EasyWeChat::miniProgram();
       
        // $userMsg = $mini->auth->session($code); // $code 为wx.login里的code
        // 如果code正确的话，那么此时$result 里就会包含openid， session_key等信息。
        // 此时可以将openid存储到自己需要的数据库中
        // $result['openid'] = 'your-openid';
        $payment = \EasyWeChat::payment(); // 微信支付
        $result = $payment->order->unify([
            'body'         => request('title'),
            'out_trade_no' => time(true).mt_rand().'',
            'trade_type'   => 'JSAPI',  // 必须为JSAPI
            'openid'       => 'oGLpd5TRJvpg-xU-w6y9mvsPocSc', // 这里的openid为付款人的openid
            'total_fee'    => request('price'), // 总价
        ]);

        // 如果成功生成统一下单的订单，那么进行二次签名
        if ($result['return_code'] === 'SUCCESS') {
            // 二次签名的参数必须与下面相同
            // return $result;
            $params = [
                'appId'     => 'wxb67fbbdc5d47be45',
                'timeStamp' => time().'',
                'nonceStr'  => $result['nonce_str'],
                'package'   => 'prepay_id=' . $result['prepay_id'],
                'signType'  => 'MD5',
            ];

            // config('wechat.payment.default.key')为商户的key
            $params['paySign'] = generate_sign($params, config('wechat.payment.default.key'));

            return json_encode($params);
        } else {
            return json_encode($result);
        }
        // 如果成功二次签名，返回的结果与下面的类似
        // $params = {
        //      "appId": "wxedsadwvsager343df5",
        //      "timeStamp": 1520515252,
        //      "nonceStr": "wGs9JOpqKQcJYf7m",
        //      "package": "prepay_id=wx201803cdsqa2ae1e202110689669353",
        //      "signType": "MD5",
        //      "paySign": "398729A0461F5A825DA169CA29721038"
        //  }



    }
    public  function wxnotify(Request $request){
         
    }
     // 请求微信接口的公用配置, 所以单独提出来
    private function payment()
    {
        $config = [
            // 必要配置, 这些都是之前在 .env 里配置好的
            'app_id' => config('wechat.payment.default.app_id'),
            'mch_id' => config('wechat.payment.default.mch_id'),
            'key'    => config('wechat.payment.default.key'),   // API 密钥
            'notify_url' => config('wechat.payment.default.notify_url'),   // 通知地址
        ];
        // 这个就是 easywechat 封装的了, 一行代码搞定, 照着写就行了
        $app = Factory::payment($config);

        return $app;
    }

    // 向微信请求统一下单接口, 创建预支付订单
    public function place_order($id)
    {
        // 获取商品id
        $id=request('id');
        // 因为没有先创建订单, 所以这里先生成一个随机的订单号, 存在 pay_log 里, 用来标识订单, 支付成功后再把这个订单号存到 order 表里
        $order_sn = date('ymd').substr(time(),-5).substr(microtime(),2,5);
        // 根据文章 id 查出文章价格
        $post_price = DB::table('shop')->where(['id'=>$id])->first()->price;
        // 数据库创建 Paylog 记录
        DB::table('payLog')->insert([
            'appid' => config('wechat.payment.default.app_id'),
            'mch_id' => config('wechat.payment.default.mch_id'),
            'out_trade_no' => $order_sn,
            'post_id' => $id
        ]);
       

        $app = $this->payment();

        $total_fee = env('APP_DEBUG') ? 1 : $post_price;
        // 用 easywechat 封装的方法请求微信的统一下单接口
        $result = $app->order->unify([
            'trade_type'       => 'NATIVE', // 原生支付即扫码支付，商户根据微信支付协议格式生成的二维码，用户通过微信“扫一扫”扫描二维码后即进入付款确认界面，输入密码即完成支付。  
            'body'             => '投资平台-订单支付', // 这个就是会展示在用户手机上巨款界面的一句话, 随便写的
            'out_trade_no'     => $order_sn,
            'total_fee'        => $total_fee,
            'spbill_create_ip' => request()->ip(), // 可选，如不传该参数，SDK 将会自动获取相应 IP 地址
        ]);

        if ($result['result_code'] == 'SUCCESS') {
            // 如果请求成功, 微信会返回一个 'code_url' 用于生成二维码
            $code_url = $result['code_url'];
            return [
                'code' => 200,
                // 订单编号, 用于在当前页面向微信服务器发起订单状态查询请求
                'order_sn' => $order_sn,
                // 生成二维码
                'html' => QrCode::size(200)->generate($code_url),
            ];
        }
    }
    // 接收微信支付状态的通知
    public function notify()
    {
        $app = $this->payment();
        // 用 easywechat 封装的方法接收微信的信息, 根据 $message 的内容进行处理, 之后要告知微信服务器处理好了, 否则微信会一直请求这个 url, 发送信息
        $response = $app->handlePaidNotify(function($message, $fail){
            // 首先查看 order 表, 如果 order 表有记录, 表示已经支付过了
            $order = DB::table('order')->where('order_sn', $message['out_trade_no'])->first();
            if ($order) {
                return true; // 如果已经生成订单, 表示已经处理完了, 告诉微信不用再通知了
            }

            // 查看支付日志
            // $payLog = DB::table('payLog')->where('out_trade_no', $message['out_trade_no'])->first();
            // if (!$payLog || $payLog->paid_at) { // 如果订单不存在 或者 订单已经支付过了
            //     return true; // 告诉微信，我已经处理完了，订单没找到，别再通知我了
            // }

            // return_code 表示通信状态，不代表支付状态
            if ($message['return_code'] === 'SUCCESS') {
                // 用户是否支付成功
                if ($message['result_code'] === 'SUCCESS') {
                    // 更新支付时间为当前时间
                    // $payLog->paid_at = now();
                    // $post_id = '';
                    // // 联表查询 post 的相关信息
                    // $post_title = '';
                    // $post_price = '';
                    // $post_original_price = '';
                    // $post_cover = '';
                    // $post_description = '';
                    // $user_id = '';

                    // 创建订单记录
                    DB::table('order')->insert([
                        'order_sn' => $message['out_trade_no'],
                    ]);

                    // 更新 PayLog, 这里的字段都是根据微信支付结果通知的字段设置的(https://pay.weixin.qq.com/wiki/doc/api/native.php?chapter=9_7&index=8)
                    // DB::table('payLog')->where('out_trade_no', $message['out_trade_no'])->update([
                    //     'appid' => $message['appid'],
                    //     'bank_type' => $message['bank_type'],
                    //     'total_fee' => $message['total_fee'],
                    //     'trade_type' => $message['trade_type'],
                    //     'is_subscribe' => $message['is_subscribe'],
                    //     'mch_id' => $message['mch_id'],
                    //     'nonce_str' => $message['nonce_str'],
                    //     'openid' => $message['openid'],
                    //     'sign' => $message['sign'],
                    //     'cash_fee' => $message['cash_fee'],
                    //     'fee_type' => $message['fee_type'],
                    //     'transaction_id' => $message['transaction_id'],
                    //     'time_end' => $payLog->paid_at,
                    //     'result_code' => $message['result_code'],
                    //     'return_code' => $message['return_code'],
                    // ]);
                }
            } else {

                // 如果支付失败, 也更新 PayLog, 跟上面一样, 就是多了 error 信息
                // DB::table('payLog')->where('out_trade_no', $message['out_trade_no'])->update([
                //     'appid' => $message['appid'],
                //     'bank_type' => $message['bank_type'],
                //     'total_fee' => $message['total_fee'],
                //     'trade_type' => $message['trade_type'],
                //     'is_subscribe' => $message['is_subscribe'],
                //     'mch_id' => $message['mch_id'],
                //     'nonce_str' => $message['nonce_str'],
                //     'openid' => $message['openid'],
                //     'sign' => $message['sign'],
                //     'cash_fee' => $message['cash_fee'],
                //     'fee_type' => $message['fee_type'],
                //     'transaction_id' => $message['transaction_id'],
                //     'time_end' => $payLog->paid_at,
                //     'result_code' => $message['result_code'],
                //     'return_code' => $message['return_code'],
                //     'err_code' => $message['err_code'],
                //     'err_code_des' => $message['err_code_des'],
                // ]);
                return $fail('通信失败，请稍后再通知我');
            }
            return true; // 返回处理完成
        });
        // 这里是必须这样返回的, 会发送给微信服务器处理结果
        return $response;
    }
    public function paid(Request $request)
    {
        $out_trade_no = $request->get('out_trade_no');

        $app = $this->payment();
        // 用 easywechat 封装的方法请求微信
        $result = $app->order->queryByOutTradeNumber($out_trade_no);

        if ($result['trade_state'] === 'SUCCESS'){ 
            return [
                'code' => 200,
                'msg' => 'paid'
            ];
        }else{
            return [
                'code' => 202,
                'msg' => 'not paid'
            ];
        }
    }
    // 用于数据库迁移
    public function up()
    {
        Schema::create('pay_logs', function (Blueprint $table) {
            $table->bigIncrements('id');

            // 根据自身业务设计的字段
            $table->integer('post_id')->default(0)->comment('文章id');
            // 以下均是微信支付结果通知接口返回的字段
            $table->string('appid', 255)->default('')->comment('微信分配的公众账号ID');
            $table->string('mch_id', 255)->default('')->comment('微信支付分配的商户号');
            $table->string('bank_type', 16)->default('')->comment('付款银行');
            $table->integer('cash_fee')->default(0)->comment('现金支付金额');
            $table->string('fee_type', 8)->default('')->comment('货币种类');
            $table->string('is_subscribe', 1)->default('')->comment('是否关注公众账号');
            $table->string('nonce_str', 32)->default('')->comment('随机字符串');
            $table->string('openid', 128)->default('')->comment('用户标识');
            $table->string('out_trade_no', 32)->default('')->comment('商户系统内部订单号');
            $table->string('result_code', 16)->default('')->comment('业务结果');
            $table->string('return_code', 16)->default('')->comment('通信标识');
            $table->string('sign', 32)->default('')->comment('签名');
            $table->string('prepay_id', 64)->default('')->comment('微信生成的预支付回话标识，用于后续接口调用中使用，该值有效期为2小时');
            $table->dateTime('time_end')->nullable()->comment('支付完成时间');
            $table->integer('total_fee')->default(0)->comment('订单金额');
            $table->string('trade_type', 16)->default('')->comment('交易类型');
            $table->string('transaction_id', 32)->default('')->comment('微信支付订单号');
            $table->string('err_code', 32)->default('')->comment('错误代码');
            $table->string('err_code_des', 128)->default('')->comment('错误代码描述');
            $table->string('device_info', 32)->default('')->comment('设备号');
            $table->text('attach')->nullable()->comment('商家数据包');

            $table->nullableTimestamps();
        });
    }
 
         


}





 ?>