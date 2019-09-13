"use strict";
var __extends = (this && this.__extends) || (function () {
    var extendStatics = function (d, b) {
        extendStatics = Object.setPrototypeOf ||
            ({ __proto__: [] } instanceof Array && function (d, b) { d.__proto__ = b; }) ||
            function (d, b) { for (var p in b) if (b.hasOwnProperty(p)) d[p] = b[p]; };
        return extendStatics(d, b);
    };
    return function (d, b) {
        extendStatics(d, b);
        function __() { this.constructor = d; }
        d.prototype = b === null ? Object.create(b) : (__.prototype = b.prototype, new __());
    };
})();
var __importDefault = (this && this.__importDefault) || function (mod) {
    return (mod && mod.__esModule) ? mod : { "default": mod };
};
Object.defineProperty(exports, "__esModule", { value: true });
var fs_1 = __importDefault(require("fs"));
var xml2js_1 = __importDefault(require("xml2js"));
var util_1 = require("util");
var component_1 = require("./component");
var moment_1 = __importDefault(require("moment"));
var BSGenerator = /** @class */ (function () {
    function BSGenerator(root) {
        this.model = null;
        this.root = "../../";
        this.root = root;
    }
    BSGenerator.preload = function () {
        var src = fs_1.default.readFileSync(BSGenerator.lib + "/param.json");
        BSGenerator.ppt = JSON.parse(src.toString());
        src = fs_1.default.readFileSync(this.lib + "/types.json");
        BSGenerator.types = JSON.parse(src.toString());
    };
    BSGenerator.tokenCvt = function (str) {
        if (BSGenerator.ppt) {
            if (str.startsWith("token.")) {
                str = str.substring(6);
                return BSGenerator.ppt.token.replace(/\?/g, str);
            }
            else {
                return BSGenerator.ppt.input.replace(/\?/g, str);
            }
        }
        return str;
    };
    BSGenerator.isToken = function (str) {
        return str.startsWith("token.");
    };
    BSGenerator.isConst = function (str) {
        return str.startsWith("@");
    };
    BSGenerator.constCvt = function (str) {
        return str.substring(1);
    };
    BSGenerator.isInput = function (str) {
        if (BSGenerator.isConst(str))
            return false;
        if (BSGenerator.isToken(str))
            return false;
        return true;
    };
    BSGenerator.prototype.makeDir = function () {
        if (!fs_1.default.existsSync(this.root)) {
            fs_1.default.mkdirSync(this.root);
        }
    };
    BSGenerator.prototype.makeArray = function (node) {
        if (!util_1.isArray(node)) {
            var a = new Array();
            if (node)
                a.push(node);
            node = a;
        }
        return node;
    };
    BSGenerator.prototype.preproc = function (json) {
        json = json.replace(/(@?\w[\w\d_\.]*)/g, "\"$1\"");
        return "{" + json + "}";
    };
    BSGenerator.error = function (str) {
        throw "脚本执行错误：" + str;
    };
    BSGenerator.capitalize = function (str) {
        var sps = str.split("_");
        str = "";
        for (var i = 0; i < sps.length; i++) {
            if (sps.length > 0) {
                var s = sps[i];
                s = s.substring(0, 1).toUpperCase() + s.substring(1);
                str += s;
            }
        }
        return str;
    };
    BSGenerator.attrId = function (table, attr) {
        for (var i = 0; i < table.attr.length; i++) {
            if (table.attr[i].$.name == attr)
                return i;
        }
        return -1;
    };
    BSGenerator.getAttr = function (table, attr) {
        var id = BSGenerator.attrId(table, attr);
        if (id < 0)
            BSGenerator.error("对象" + table.$.name + "没有属性" + attr);
        return table.attr[id];
    };
    BSGenerator.entityId = function (model, name) {
        for (var i = 0; i < model.entity.length; i++) {
            if (model.entity[i].$.name == name)
                return i;
        }
        return -1;
    };
    BSGenerator.getEntity = function (model, name) {
        var id = BSGenerator.entityId(model, name);
        if (id < 0)
            BSGenerator.error("对象" + name + "不存在");
        return model.entity[id];
    };
    BSGenerator.prototype.setupUtils = function (c) {
        c.props["attrId"] = BSGenerator.attrId;
        c.props["entityId"] = BSGenerator.entityId;
        c.props["attr"] = BSGenerator.getAttr;
        c.props["entity"] = BSGenerator.getEntity;
        c.props["cap"] = BSGenerator.capitalize;
        c.props["error"] = BSGenerator.error;
        c.props["tcvt"] = BSGenerator.tokenCvt;
        c.props["isTk"] = BSGenerator.isToken;
        c.props["isConst"] = BSGenerator.isConst;
        c.props["ccvt"] = BSGenerator.constCvt;
        c.props["isInput"] = BSGenerator.isInput;
        c.props["types"] = BSGenerator.types;
    };
    BSGenerator.prototype.build = function (model) {
        this.makeDir();
        this.model = model;
        model.entity = this.makeArray(model.entity);
        for (var i = 0; i < model.entity.length; i++) {
            if (!this.parseEntity(model.entity[i]))
                return false;
        }
        return this.postBuild();
    };
    BSGenerator.lib = "./lib/laravel";
    BSGenerator.types = null;
    BSGenerator.ppt = null;
    return BSGenerator;
}());
var DBGenerator = /** @class */ (function (_super) {
    __extends(DBGenerator, _super);
    function DBGenerator(dir) {
        var _this = this;
        dir = dir + "/database/migrations";
        console.log("migrations: " + dir);
        _this = _super.call(this, dir) || this;
        return _this;
    }
    DBGenerator.prototype.genDate = function () {
        return moment_1.default().format('YYYY_MM_DD_HHmm');
    };
    DBGenerator.prototype.genFName = function (name) {
        return this.root + "/" + this.genDate() + "_create_" + name + ".php";
    };
    DBGenerator.prototype.parseEntity = function (node) {
        var fn = this.genFName(node.$.name);
        var fn1 = this.genFName('dropTable');
        var comp = component_1.Component.create(BSGenerator.lib, "migration");
        var comp1 = component_1.Component.create(BSGenerator.lib, "dropTable");
        if (comp == null || comp1 == null)
            return false;
        var attrs = new Array();
        node.attr = this.makeArray(node.attr);
        for (var i = 0; i < node.attr.length; i++) {
            attrs.push(node.attr[i].$);
        }
        comp.props["cols"] = attrs;
        comp.props["name"] = node.$.name;
        comp.props["time"] = node.$.time === "true";
        comp1.props["model"] = this.model;
        this.setupUtils(comp);
        var result = comp.build();
        var result1 = comp1.build();
        fs_1.default.writeFileSync(fn, result);
        fs_1.default.writeFileSync(fn1, result1);
        return true;
    };
    DBGenerator.prototype.postBuild = function () { return true; };
    return DBGenerator;
}(BSGenerator));
var CTGenerator = /** @class */ (function (_super) {
    __extends(CTGenerator, _super);
    function CTGenerator(dir) {
        var _this = this;
        dir = dir + "/app/Http/" + "Controllers";
        console.log('Controllers: ' + dir);
        _this = _super.call(this, dir) || this;
        return _this;
    }
    CTGenerator.prototype.ctrlName = function (str) {
        return BSGenerator.capitalize(str) + "Controller";
    };
    CTGenerator.prototype.parseEntity = function (node) {
        var fn = this.root + "/" + this.ctrlName(node.$.name) + ".php";
        var comp = component_1.Component.create(BSGenerator.lib, "controller");
        if (comp == null)
            return false;
        var apis = new Array();
        node.oper = this.makeArray(node.oper);
        for (var i = 0; i < node.oper.length; i++) {
            var tp = node.oper[i].$.type;
            var c = component_1.Component.create(BSGenerator.lib + "/opers", tp);
            if (c === null) {
                BSGenerator.error("组件" + tp + "不存在");
                return false;
            }
            var json = "";
            if (util_1.isString(node.oper[i]._))
                json = node.oper[i]._;
            json = this.preproc(json);
            c.props = JSON.parse(json);
            c.props["oper"] = node.oper[i];
            c.props["table"] = node;
            c.props["model"] = this.model;
            this.setupUtils(c);
            var src = c.build();
            var api = { name: node.oper[i].$.name, body: src };
            apis.push(api);
        }
        comp.props["apis"] = apis;
        comp.props["name"] = node.$.name;
        this.setupUtils(comp);
        var result = comp.build();
        fs_1.default.writeFileSync(fn, result);
        return true;
    };
    CTGenerator.prototype.postBuild = function () { return true; };
    return CTGenerator;
}(BSGenerator));
var RTGenerator = /** @class */ (function (_super) {
    __extends(RTGenerator, _super);
    function RTGenerator(dir) {
        var _this = this;
        dir = dir + "/routes";
        _this = _super.call(this, dir) || this;
        console.log("route: " + dir);
        _this.layout = component_1.Component.create(BSGenerator.lib, "route");
        if (_this.layout != null)
            _this.setupUtils(_this.layout);
        _this.routes = new Array();
        return _this;
    }
    RTGenerator.prototype.parseEntity = function (node) {
        node.oper = this.makeArray(node.oper);
        for (var i = 0; i < node.oper.length; i++) {
            var role = node.oper[i].$.role;
            var route = { role: role, mib: node.oper[i].$.mib, ctrl: node.$.name, func: node.oper[i].$.name };
            this.routes.push(route);
        }
        return true;
    };
    RTGenerator.prototype.postBuild = function () {
        if (this.layout != null) {
            var fn = this.root + "/codecRoute.php";
            this.layout.props["routes"] = this.routes;
            var src = this.layout.build();
            fs_1.default.writeFileSync(fn, src);
            return true;
        }
        else {
            BSGenerator.error("路由布局文件routes.php未找到");
            return false;
        }
    };
    return RTGenerator;
}(BSGenerator));
var DDGenerator = /** @class */ (function (_super) {
    __extends(DDGenerator, _super);
    function DDGenerator(dir) {
        var _this = this;
        dir = dir + "/public";
        _this = _super.call(this, dir) || this;
        console.log('docs: ' + dir);
        _this.layout = component_1.Component.create(BSGenerator.lib, "dddoc");
        return _this;
    }
    DDGenerator.prototype.parseEntity = function (node) {
        return true;
    };
    DDGenerator.prototype.postBuild = function () {
        if (this.layout != null) {
            var fn = this.root + "/data.html";
            this.layout.props["model"] = this.model;
            this.setupUtils(this.layout);
            var src = this.layout.build();
            fs_1.default.writeFileSync(fn, src);
            return true;
        }
        else {
            BSGenerator.error("文档页面模板未找到");
            return false;
        }
    };
    return DDGenerator;
}(BSGenerator));
var ADGenerator = /** @class */ (function (_super) {
    __extends(ADGenerator, _super);
    function ADGenerator(dir) {
        var _this = this;
        dir = dir + "/public";
        _this = _super.call(this, dir) || this;
        console.log("docs: " + dir);
        _this.layout = component_1.Component.create(BSGenerator.lib, "apidoc");
        _this.docs = {};
        return _this;
    }
    ADGenerator.prototype.parseEntity = function (node) {
        var apis = new Array();
        node.oper = this.makeArray(node.oper);
        for (var i = 0; i < node.oper.length; i++) {
            var tp = node.oper[i].$.type;
            var c = component_1.Component.create(BSGenerator.lib + "/docs", tp);
            if (c) {
                var json = "";
                if (util_1.isString(node.oper[i]._))
                    json = node.oper[i]._;
                json = json = this.preproc(json);
                c.props = JSON.parse(json);
                c.props["oper"] = node.oper[i];
                c.props["table"] = node;
                c.props["model"] = this.model;
                this.setupUtils(c);
                c.props["mode"] = "input";
                var isrc = c.build();
                c.props["mode"] = "output";
                var osrc = c.build();
                var api = { role: node.oper[i].$.role, page: node.oper[i].$.page, name: node.oper[i].$.name, label: node.oper[i].$.label, input: isrc, output: osrc };
                apis.push(api);
            }
        }
        this.docs[node.$.name] = apis;
        return true;
    };
    ADGenerator.prototype.postBuild = function () {
        if (this.layout !== null) {
            this.layout.props["docs"] = this.docs;
            var src = this.layout.build();
            fs_1.default.writeFileSync(this.root + "/api.html", src);
            return true;
        }
        return false;
    };
    return ADGenerator;
}(BSGenerator));
var Generator = /** @class */ (function () {
    function Generator(dir) {
        this.message = "";
        BSGenerator.preload();
        this.gs = new Array();
        this.gs.push(new DBGenerator(dir));
        this.gs.push(new CTGenerator(dir));
        this.gs.push(new RTGenerator(dir));
        this.gs.push(new DDGenerator(dir));
        this.gs.push(new ADGenerator(dir));
    }
    Generator.prototype.build = function (src) {
        var _this = this;
        var model = null;
        xml2js_1.default.parseString(src, {}, function (err, result) {
            if (!result)
                _this.message = err;
            else
                model = result.model;
        });
        if (model !== null) {
            if (model.entity === null) {
                BSGenerator.error("未定义任何实体对象");
            }
            this.gs.forEach(function (g) {
                g.build(model);
            });
        }
    };
    return Generator;
}());
exports.Generator = Generator;
