"use strict";
// import fs from "fs"
// import xml2js from "xml2js"
// import {Generator} from "./generators"
var __importDefault = (this && this.__importDefault) || function (mod) {
    return (mod && mod.__esModule) ? mod : { "default": mod };
};
Object.defineProperty(exports, "__esModule", { value: true });
// class App {
//     public run(file: string) {
//         let g = new Generator("project");
//         let src = fs.readFileSync(file);
//         g.build(src.toString());
//     }
// }
// let app = new App();
// app.run("./master.xml");
var generators_1 = require("./generators");
var express_1 = __importDefault(require("express"));
var body_parser_1 = __importDefault(require("body-parser"));
// let root = "../education-test.bayou-tech.cn";
var app = express_1.default();
/**
 * @param file xml配置
 * @param root 生成路径
 */
function build(xml_config, project_path) {
    var g = new generators_1.Generator(project_path);
    g.build(xml_config);
}
function success(data) {
    if (data === void 0) { data = null; }
    var result = {
        error: 0,
        data: data
    };
    return JSON.stringify(result);
}
function fails(message) {
    var result = {
        error: 1,
        data: message
    };
    return JSON.stringify(result);
}
//设置请求最大数据
app.use(body_parser_1.default.json({ limit: '50mb' }));
app.use(body_parser_1.default.urlencoded({
    limit: '50mb',
    extended: true
}));
app.all('*', function (req, res, next) {
    res.header('Access-Control-Allow-Origin', '*');
    //Access-Control-Allow-Headers ,可根据浏览器的F12查看,把对应的粘贴在这里就行
    res.header('Access-Control-Allow-Headers', 'Content-Type');
    res.header('Access-Control-Allow-Methods', '*');
    res.header('Content-Type', 'application/json;charset=utf-8');
    next();
});
app.post('/build', function (req, res) {
    var xml_config = req.body.xml_config;
    var project_path = req.body.project_path;
    var body = req.body;
    for (var key in body) {
        console.log(key + ' = ' + body[key]);
    }
    console.log('xml_config: ' + xml_config);
    console.log('project_path: ' + project_path);
    if (xml_config != null) {
        try {
            build(xml_config, project_path);
        }
        catch (e) {
            var msg = "生成失败: " + e;
            res.send(fails(msg));
            console.log(msg);
            return;
        }
        res.send(success("生成成功"));
    }
    else {
        res.send(fails("请求无效！"));
    }
});
var server = app.listen(8082, function () {
    if (server != null) {
        console.log("服务已启动");
    }
    else {
        console.log("启动失败");
    }
});
