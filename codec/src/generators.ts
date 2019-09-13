import fs from "fs"
import xml2js from "xml2js"
import { isArray, isString } from "util"
import { Component } from "./component"
import moment from "moment"

abstract class BSGenerator {

    protected model: any = null;
    protected root: string = "../../";
    protected static lib = "./lib/laravel";
    protected static types: any = null;

    protected static ppt: any = null;

    public static preload() {
        let src = fs.readFileSync(BSGenerator.lib + "/param.json");
        BSGenerator.ppt = JSON.parse(src.toString());
        src = fs.readFileSync(this.lib + "/types.json");
        BSGenerator.types = JSON.parse(src.toString());
    }

    public static tokenCvt(str: string): string {
        if (BSGenerator.ppt) {
            if (str.startsWith("token.")) {
                str = str.substring(6);
                return BSGenerator.ppt.token.replace(/\?/g, str);
            } else {
                return BSGenerator.ppt.input.replace(/\?/g, str);
            }
        }
        return str;
    }

    public static isToken(str: string): boolean {
        return str.startsWith("token.");
    }

    public static isConst(str: string): boolean {
        return str.startsWith("@");
    }

    public static constCvt(str: string): string {
        return str.substring(1);
    }

    public static isInput(str: string): boolean {
        if (BSGenerator.isConst(str))
            return false;
        if (BSGenerator.isToken(str))
            return false;
        return true;
    }

    public constructor(root: string) {
        this.root = root;
    }

    private makeDir(): void {
        if (!fs.existsSync(this.root)) {
            fs.mkdirSync(this.root);
        }
    }

    protected makeArray(node: any): any {
        if (!isArray(node)) {
            let a = new Array<any>();
            if (node) a.push(node);
            node = a;
        }
        return node;
    }

    protected preproc(json: string): string {
        json = json.replace(/(@?\w[\w\d_\.]*)/g, "\"$1\"");
        return "{" + json + "}";
    }
    
    public static error(str: string): void {
        throw "脚本执行错误：" + str;
    }

    public static capitalize(str: string): string {
        let sps = str.split("_");
        str = "";
        for (let i = 0; i < sps.length; i++) {
            if (sps.length > 0) {
                let s = sps[i];
                s = s.substring(0, 1).toUpperCase() + s.substring(1);
                str += s;
            }
        }
        return str;
    }
    
    public static attrId(table: any, attr: string): number {
        for (let i = 0; i < table.attr.length; i++) {
            if (table.attr[i].$.name == attr)
                return i;
        }
        return -1;
    }

    public static getAttr(table: any, attr: string): any {
        let id = BSGenerator.attrId(table, attr);
        if (id < 0)
            BSGenerator.error("对象" + table.$.name + "没有属性" + attr);
        return table.attr[id];
    }

    public static entityId(model: any, name: string): number {
        for (let i = 0; i < model.entity.length; i++) {
            if (model.entity[i].$.name == name)
                return i;
        }
        return -1;
    }

    public static getEntity(model: any, name: string): any {
        let id = BSGenerator.entityId(model, name);
        if (id < 0)
            BSGenerator.error("对象" + name + "不存在");
        return model.entity[id];
    }

    protected setupUtils(c: Component): void {
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
    }

    public build(model: any): boolean {
        this.makeDir();
        this.model = model;
        model.entity = this.makeArray(model.entity);
        for (let i = 0; i < model.entity.length; i++) {
            if (!this.parseEntity(model.entity[i]))
                return false;
        }
        return this.postBuild();
    }

    protected abstract parseEntity(node: any): boolean;
    protected abstract postBuild(): boolean;
}

class DBGenerator extends BSGenerator {
    
    public constructor(dir: string) {
        dir = dir + "/database/migrations";
        console.log("migrations: " + dir);
        super(dir);
    }

    private genDate(): string {
        return moment().format('YYYY_MM_DD_HHmm');
    }

    private genFName(name: string): string {
        return this.root + "/" + this.genDate() + "_create_" + name + ".php";
    }

    protected parseEntity(node: any): boolean {
        let fn = this.genFName(node.$.name);
        let fn1 = this.genFName('dropTable');
        let comp = Component.create(BSGenerator.lib, "migration");
        let comp1 = Component.create(BSGenerator.lib, "dropTable");
        if (comp == null || comp1 == null)
            return false;
        let attrs = new Array<any>();
        node.attr = this.makeArray(node.attr);
        for (let i = 0; i < node.attr.length; i++) {
            attrs.push(node.attr[i].$);
        }
        comp.props["cols"] = attrs;
        comp.props["name"] = node.$.name;
        comp.props["time"] = node.$.time === "true";
        comp1.props["model"] = this.model;
        this.setupUtils(comp);
        let result = comp.build();
        let result1 = comp1.build();
        fs.writeFileSync(fn, result);
        fs.writeFileSync(fn1, result1);
        return true;
    }

    protected postBuild(): boolean { return true; }
}

class CTGenerator extends BSGenerator {

    public constructor(dir: string) {
        dir = dir +"/app/Http/" + "Controllers";
        console.log('Controllers: ' + dir);
        super(dir);
    }

    private ctrlName(str: string): string {
        return BSGenerator.capitalize(str) + "Controller";
    }

    protected parseEntity(node: any): boolean {
        let fn = this.root + "/" + this.ctrlName(node.$.name) + ".php";
        let comp = Component.create(BSGenerator.lib, "controller");
        if (comp == null)
            return false;
        let apis = new Array<any>();
        node.oper = this.makeArray(node.oper);
        for (let i = 0; i < node.oper.length; i++) {
            let tp = node.oper[i].$.type;
            let c = Component.create(BSGenerator.lib + "/opers", tp);
            if (c === null) {
                BSGenerator.error("组件" + tp + "不存在");
                return false;
            }
            let json = "";
            if (isString(node.oper[i]._))
                json = node.oper[i]._;
            json = this.preproc(json);
            c.props = JSON.parse(json);
            c.props["oper"] = node.oper[i];
            c.props["table"] = node;
            c.props["model"] = this.model;
            this.setupUtils(c);
            let src = c.build();
            let api = {name: node.oper[i].$.name, body: src};
            apis.push(api);
        }
        comp.props["apis"] = apis;
        comp.props["name"] = node.$.name;
        this.setupUtils(comp);
        let result = comp.build();
        fs.writeFileSync(fn, result);
        return true;
    }

    protected postBuild(): boolean { return true; }
}

class RTGenerator extends BSGenerator {

    private layout: Component | null;
    private routes: Array<any>;

    public constructor(dir: string) {
        dir = dir + "/routes";
        super(dir);
        console.log("route: " + dir);
        this.layout = Component.create(BSGenerator.lib, "route");
        if(this.layout != null)
            this.setupUtils(this.layout);
        this.routes = new Array<any>();
    }

    protected parseEntity(node: any): boolean {
        node.oper = this.makeArray(node.oper);
        for (let i = 0; i < node.oper.length; i++) {
            let role = node.oper[i].$.role;
            let route = {role: role, mib: node.oper[i].$.mib, ctrl: node.$.name, func: node.oper[i].$.name};
            this.routes.push(route);
        }
        return true;
    }
    
    protected postBuild(): boolean {
        if (this.layout != null) {
            let fn = this.root + "/codecRoute.php";
            this.layout.props["routes"] = this.routes;
            let src = this.layout.build();
            fs.writeFileSync(fn, src);
            return true;
        } else {
            BSGenerator.error("路由布局文件routes.php未找到");
            return false;
        }
    }
}

class DDGenerator extends BSGenerator {

    private layout: Component | null;

    public constructor(dir: string) {
        dir = dir + "/public";
        super(dir);
        console.log('docs: ' + dir);
        this.layout = Component.create(BSGenerator.lib, "dddoc");
    }

    protected parseEntity(node: any): boolean {
        return true;
    }
    
    protected postBuild(): boolean {
        if (this.layout != null) {
            let fn = this.root + "/data.html";
            this.layout.props["model"] = this.model;
            this.setupUtils(this.layout);
            let src = this.layout.build();
            fs.writeFileSync(fn, src);
            return true;
        } else {
            BSGenerator.error("文档页面模板未找到");
            return false;
        }
    }
}

class ADGenerator extends BSGenerator {

    private layout: Component | null;
    private docs: any;

    public constructor(dir: string) {
        dir = dir +"/public";
        super(dir);
        console.log("docs: " + dir);
        this.layout = Component.create(BSGenerator.lib, "apidoc");
        this.docs = {};
    }

    protected parseEntity(node: any): boolean {
        let apis = new Array<any>();
        node.oper = this.makeArray(node.oper);
        for (let i = 0; i < node.oper.length; i++) {
            let tp = node.oper[i].$.type;
            let c = Component.create(BSGenerator.lib + "/docs", tp);
            if (c) {
                let json = "";
                if (isString(node.oper[i]._))
                    json = node.oper[i]._;
                json = json = this.preproc(json);
                c.props = JSON.parse(json);
                c.props["oper"] = node.oper[i];
                c.props["table"] = node;
                c.props["model"] = this.model;
                this.setupUtils(c);
                c.props["mode"] = "input";
                let isrc = c.build();
                c.props["mode"] = "output";
                let osrc = c.build();
                let api = {role: node.oper[i].$.role, page: node.oper[i].$.page, name: node.oper[i].$.name, label: node.oper[i].$.label, input: isrc, output: osrc};
                apis.push(api);
            }
        }
        this.docs[node.$.name] = apis;
        return true;
    }

    protected postBuild(): boolean {
        if (this.layout !== null) {
            this.layout.props["docs"] = this.docs;
            let src = this.layout.build();
            fs.writeFileSync(this.root + "/api.html", src);
            return true;
        } 
        return false;
    }
}

export class Generator {

    public message: string = "";
    private gs: Array<BSGenerator>;

    public constructor(dir: string) {
        BSGenerator.preload();
        this.gs = new Array<BSGenerator>();
        this.gs.push(new DBGenerator(dir));
        this.gs.push(new CTGenerator(dir));
        this.gs.push(new RTGenerator(dir));
        this.gs.push(new DDGenerator(dir));
        this.gs.push(new ADGenerator(dir));
    }

    public build(src: string): void {
        let model: any = null;
        xml2js.parseString(src, {}, (err, result) => {
            if (!result)
                this.message = err;
            else model = result.model;
        });
        if (model !== null) {
            if (model.entity === null) {
                BSGenerator.error("未定义任何实体对象");
            }
            this.gs.forEach((g: BSGenerator) => {
                g.build(model);
            });
        }
    }
}