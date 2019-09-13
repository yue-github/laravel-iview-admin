<?php
namespace App\Console\Commands;
use Illuminate\Console\Command;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
class Swoole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'swoole {action?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'swoole';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $action = $this->argument('action');
        switch ($action) {
            case 'close':
                break;
            default:
                $this->start();
                break;
        }

    }
    public function start()
    {
        //创建websocket服务器对象，监听0.0.0.0:9450端口
         $ws = new \swoole_websocket_server("coral3.com", 2333, SWOOLE_BASE, SWOOLE_SOCK_TCP | SWOOLE_SSL);
         $ws->set([   
            'worker_num' => 1,          
            'daemonize' => true,          
            'log_file' => __DIR__ . '/server.log',
            'ssl_key_file' => '/www/server/panel/vhost/ssl/coral3.com/privkey.pem',
            'ssl_cert_file' => '/www/server/panel/vhost/ssl/coral3.com/fullchain.pem'
        ]);   

        //监听WebSocket连接打开事件
        $ws->on('open', function ($ws, $request) {
            $maxId=DB::table('user_visit')->max('id');
            $maxCount=json_decode(DB::table('user_visit')->where(['id'=>$maxId])->get(),true)[0]['visit_count'];
            $nowCount=intval($maxCount)+1;
            $result=DB::table('user_visit')->insert(['visit_count'=>$nowCount]);
            $ws->push($request->fd, "连接成功~");
        });

        //监听WebSocket消息事件
        $ws->on('message', function ($ws, $frame) {
           $maxId=DB::table('user_visit')->max('id');
           $result= DB::table('user_visit')->where(['id'=>$maxId])->get();
            foreach($ws->connection_list() as $user){
                $ws->push($user,$result.'');
            }
             
        });
        //监听WebSocket连接关闭事件
        $ws->on('close', function ($ws, $fd) {
            echo "client-{$fd} is closed\n";
             // $maxId=DB::table('user_visit')->max('id');
            // $maxCount=json_decode(DB::table('user_visit')->where(['id'=>$maxId])->get(['visit_count']),true)[0]['visit_count'];
            // $nowCount=intval($maxCount)+1;
            // DB::table('user_visit')->insert(['date'=>date('Y-m-d H:i:s',time()),'visit_count'=>$nowCount]);
        });
        $ws->start();
    }
}
