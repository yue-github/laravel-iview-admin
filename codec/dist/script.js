"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
var token = /** @class */ (function () {
    function token() {
        this.name = "";
        this.type = 0;
    }
    return token;
}());
var Script = /** @class */ (function () {
    function Script() {
        this.message = "";
        this.i = 0;
        this.s = "";
        this.t = "";
    }
    Script.prototype.isWhiteSpaces = function () {
        var c = this.s[this.i];
        if (c === ' ')
            return true;
        if (c === '\t')
            return true;
        if (c === '\r')
            return true;
        if (c === '\n')
            return true;
        return false;
    };
    Script.prototype.isEnd = function () {
        return this.i < this.s.length;
    };
    Script.prototype.isLetter = function () {
        var c = this.s[this.i];
        return /[a-zA-Z_]/.test(c);
    };
    Script.prototype.isLetterOrDigit = function () {
        var c = this.s[this.i];
        return /[a-zA-Z_0-9]/.test(c);
    };
    Script.prototype.isChar = function (n) {
        var c = this.s[this.i];
        for (var i = 0; i < n.length; i++) {
            if (c === n[i])
                return i;
        }
        return -1;
    };
    Script.prototype.clip = function (id) {
        this.t = this.s.substr(id, this.i - id);
    };
    Script.prototype.next = function () {
        if (this.isEnd())
            return 0;
        while (this.isWhiteSpaces())
            this.i++;
        if (this.isEnd())
            return 0;
        var j = this.i;
        if (this.isLetter()) {
            while (this.isLetterOrDigit())
                this.i++;
            this.clip(j);
            return 1;
        }
        var n = this.isChar('{}');
        if (n > -1) {
            this.i++;
            this.clip(j);
            return n + 1;
        }
        else {
            this.i++;
            this.clip(j);
            return -1;
        }
    };
    Script.prototype.parse = function (src) {
        this.i = 0;
        this.s = src;
        while (!this.isEnd()) {
            var t = this.next();
        }
    };
    return Script;
}());
exports.Script = Script;
