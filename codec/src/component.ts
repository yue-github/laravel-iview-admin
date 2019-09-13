import fs from "fs";
import ejs from "ejs";

export class Component {

    public props: any = {};
    private tmplFunc: ejs.TemplateFunction | null = null;

    public constructor(root: string, name: string) {
        let fn = root + "/" + name + ".json";
        if (fs.existsSync(fn)) {
            let buf = fs.readFileSync(fn);
            let str = buf.toString();
            this.props = JSON.parse(str);
        }
        fn = root + "/" + name + ".ejs";
        let text = fs.readFileSync(fn).toString();
        let ops = {filename: fn, outputFunctionName: 'echo'};
        this.tmplFunc = ejs.compile(text, ops);
    }
    public build(): string {
        if (this.tmplFunc !== null)
            return this.tmplFunc(this.props);
        return "";
    }
    public reset(): void {
        for (let key in this.props) {
            this.props[key] = "";
        }
    }
    public static create(root: string, name: string): Component | null {
        let tfn = root + "/" + name + ".ejs";
        if (!fs.existsSync(tfn))
            return null;
        return new Component(root, name);
    }
}