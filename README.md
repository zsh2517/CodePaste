# CodePaste
一个基于PHP的轻便的Paste服务（也算是PHP练习项目？？）

演示/使用地址[p.zsh2517.com](http://p.zsh2517.com/)

~~**目前处于调试阶段，可能会存在删库等情况**~~

---

提示: 这个和同类paste服务网站一样，都是贴代码的

1. language是语言标注，实际是用来标注高亮风格的，纯文本是plaintext（遵循highlight.js）

2. author是作者，或者来源之类的吧

3. expiration到期时间，单位是秒，常见的是1天86400秒，1周604800秒，如果输入-1则永久

4. password是访问密码，留空不设置

5. code直接粘贴过去就行

为什么不用同类已有的平台（比如UbuntuPaste或者PrivateBin）

不好找到自己贴过的东西，而且偶尔想翻一个自己以前贴过的东西的时候，已经过期。这个可以避免这种情况（服务器是明文存储的，而且可以修改过期时间，之前用PrivateBin，很安全，但是我自己都看不了╮(╯▽╰)╭）

---

高亮使用了开源项目

[Highlight.js](https://github.com/highlightjs/highlight.js)
