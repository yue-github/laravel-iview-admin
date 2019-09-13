"use strict";
var __importDefault = (this && this.__importDefault) || function (mod) {
    return (mod && mod.__esModule) ? mod : { "default": mod };
};
Object.defineProperty(exports, "__esModule", { value: true });
var fs_1 = __importDefault(require("fs"));
var generators_1 = require("./generators");
var App = /** @class */ (function () {
    function App() {
    }
    App.prototype.run = function (file) {
        var g = new generators_1.Generator("./project");
        var src = fs_1.default.readFileSync(file);
        g.build(src.toString());
    };
    return App;
}());
var app = new App();
app.run("./test.xml");
// import {Generator} from "./generators"
// import express from 'express';
// import bodyParser from 'body-parser';
// let app = express();
// /**
//  * @param file xml配置
//  * @param root 生成路径
//  */
// function build(xml_config: string, project_path: string) {
//     let g = new Generator(project_path);
//     g.build(xml_config);
// }
// let nodeCmd = require('node-cmd');
// function runCommand(projectPath: string): void {
//     //原理：
//     //子进程并不是bash进程，进程只是一个空间，用来运行某个软件。其中bash就是其中一个软件。
//     //spawn函数返回的，就是这个软件的上下文。可以向该上下文发生命令。执行程序。
//     var spawn = require('child_process').spawn;//子进程操作模块
//     // var subProcess = spawn("/bin/bash");//使用子程序去运行某个软件。在这里就是运行bash软件。并获取其上下文。
//     var subProcess = spawn("bash");//使用子程序去运行某个软件。在这里就是运行bash软件。并获取其上下文。
//     //消息监听，监听子进程的输出。并在主进程中打印出来。
//     function onData(data: any) {
//         process.stdout.write(data);//获取当前进程，并在输出中写入某内容。关键是process表示的是当前进程
//     }
//     //整个进程的错误监听
//     subProcess.on('error', function () {
//         console.log("error");
//         console.log(arguments);
//     });
//     //设置消息监听
//     subProcess.stdout.on('data', onData);
//     subProcess.stderr.on('data', onData);
//     subProcess.on('close', (code: any) => { console.log('子进程退出'); }); // 监听进程退出
//     //向子进程发送命令
//     subProcess.stdin.write('cd /home/www/' + projectPath.split('/')[1] + ' \n');   // 写入数据
//     subProcess.stdin.write('php artisan migrate \n');   // 写入数据
//     subProcess.stdin.write('rm -rf /home/www/' + projectPath.split('/')[1] + '/database/migrations' + '\n');   // 写入数据
//     subProcess.stdin.end();
// }
// function success(data: any = null): string {
//     let result = {
//         error: 0,
//         data: data
//     }
//     return JSON.stringify(result);
// }
// function fails(message: string): string {
//     let result = {
//         error: 1,
//         data: message
//     }
//     return JSON.stringify(result);
// }
// //设置请求最大数据
// app.use(bodyParser.json({limit: '50mb'}));
// app.use(bodyParser.urlencoded({ 
//     limit: '50mb',   
//     extended: true
// }));
// app.all('*', function (req: any, res: any, next: any) {
//     res.header('Access-Control-Allow-Origin', '*');
//     //Access-Control-Allow-Headers ,可根据浏览器的F12查看,把对应的粘贴在这里就行
//     res.header('Access-Control-Allow-Headers', 'Content-Type');
//     res.header('Access-Control-Allow-Methods', '*');
//     res.header('Content-Type', 'application/json;charset=utf-8');
//     next();
// });
// app.post('/build', function (req: any, res: any) {
//     let xml_config = req.body.xml_config;
//     let project_path = req.body.project_path;
//     console.log('project_path: ' + project_path);
//     if (xml_config != null) {
//         try {
//             build(xml_config, project_path);
//             res.send(success("生成成功"));
//             runCommand(project_path);
//         } catch (e) {
//             let msg = "生成失败: " + e
//             res.send(fails(msg));
//             console.log(msg);
//             return;
//         }
//     } else {
//         res.send(fails("请求无效！"));
//     }
// })
// let server = app.listen(8082, function () {
//     if (server != null) {
//         console.log("服务已启动");
//     } else {
//         console.log("启动失败");
//     }
// });
