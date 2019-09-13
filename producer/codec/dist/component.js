"use strict";
var __importDefault = (this && this.__importDefault) || function (mod) {
    return (mod && mod.__esModule) ? mod : { "default": mod };
};
Object.defineProperty(exports, "__esModule", { value: true });
var fs_1 = __importDefault(require("fs"));
var ejs_1 = __importDefault(require("ejs"));
var Component = /** @class */ (function () {
    function Component(root, name) {
        this.props = {};
        this.tmplFunc = null;
        var fn = root + "/" + name + ".json";
        if (fs_1.default.existsSync(fn)) {
            var buf = fs_1.default.readFileSync(fn);
            var str = buf.toString();
            this.props = JSON.parse(str);
        }
        fn = root + "/" + name + ".ejs";
        var text = fs_1.default.readFileSync(fn).toString();
        var ops = { filename: fn, outputFunctionName: 'echo' };
        this.tmplFunc = ejs_1.default.compile(text, ops);
    }
    Component.prototype.build = function () {
        if (this.tmplFunc !== null)
            return this.tmplFunc(this.props);
        return "";
    };
    Component.prototype.reset = function () {
        for (var key in this.props) {
            this.props[key] = "";
        }
    };
    Component.create = function (root, name) {
        var tfn = root + "/" + name + ".ejs";
        if (!fs_1.default.existsSync(tfn))
            return null;
        return new Component(root, name);
    };
    return Component;
}());
exports.Component = Component;
