# -*- coding: UTF-8 -*-
# https://docs.microsoft.com/en-us/windows/win32/shell/context-menu-handlers#completing-verb-implementation-tasks
import sys
import os
import requests
import json
import html
import pyperclip
import chardet
def get_encoding(file):
    # 二进制方式读取，获取字节数据，检测类型
    with open(file, 'rb') as f:
        data = f.read()
        return chardet.detect(data)['encoding']
url="http://www.example.com/"
# paste服务的URL，不包含具体页面，最后有个 / 
url_proc=url+"proc.php"
cmd=sys.argv
# print(cmd)
# exit(0)
language=""
if cmd[1]=="-pl":
    language=cmd[2]
    cmd[2]=cmd[3]
    cmd[1]="-p"
if cmd[1] == "-pauto":
    filename=cmd[2]
    temp=filename.split(".")
    language=temp[len(temp)-1]
    cmd[1]="-p"
if cmd[1]=="-p":
    file=cmd[2].split("\\")
    title=file[len(file)-1]
    encoding=get_encoding(cmd[2])
    print("文件编码：%s" %(encoding))
    if (os.path.exists(cmd[2]) and os.path.isfile(cmd[2])):
        file=open(cmd[2],encoding=encoding)
        author="zsh"
        str=file.read()
        print("请输入作者(留空是%s)"%(author))
        author = input()
        if author == "":
            author = "zsh"
            print(author)
        print("请输入过期时间(留空是-1)")
        expiration = input()
        if expiration == "":
            expiration = "-1"
        print("请输入密码(留空不设置密码)")
        password = input()
        print("请输入语言(留空是%s)"%(language))
        languagetemp = input()
        if languagetemp!="":
            language = languagetemp
        print("请输入标题(留空是%s)"%(title))
        languagetemp = input()
        if languagetemp != "":
            file = languagetemp
        data={
            "author":author,
            "expiration":expiration,
            "password":password,
            "language":language,
            "code":html.escape(str),
            "api":"python",
            "title":title
        }
        jsoninfo = json.dumps(data)
        response=requests.post(url=url_proc,data=data)
        # Paste这个文件
        # os.system("start ".url."paste.php?id=".response.text)
        print(response.text)
        print(url+"paste.php?id="+response.text)
        pyperclip.copy(url+"paste.php?id="+response.text)
        os.system("pause")
        exit(0)
    else:
        print("失败，文件不存在或者这个是目录")
        exit(0)
if cmd[1]=="-c":
    # 创建这个文件
    print("请输入paste服务的地址")
    inputid=input()
    if inputid[0:4]=="http":
        lines=inputid.split("=")
        inputid=lines[len(lines)-1]
    ret=requests.post(url+"paste_onlycode.php?id="+inputid)
    while ret.text[0]=="1":
        print("请输入密码")
        password=input()
        ret = requests.post(url + "paste_onlycode.php?id=" + inputid,data={"password":password})
    if(ret.text[0]=="3"):
        print("内容已过期，无法查看，任意键关闭")
        os.system("pause>nul")
        exit(0)
    ret=ret.text[1:]
    ret=json.loads(ret)
    file=open(cmd[2],"w")
    code=html.unescape(ret["code"])
    code=code.replace("\r\n","\n")
    file.write(code)
    file.close()
    if "title" not in ret: # BUG
        ret["title"]="Untitled."+ret["language"]
    print("请输入文件名(默认%s)"%(ret["title"]))
    filename=input()
    if filename=="":
        filename=ret["title"]
    # exit(0)
    file_dir=cmd[2].split("\\" )
    dir=""
    for index in range(len(file_dir)-1):
        dir=dir+file_dir[index]+"/"
    os.rename(cmd[2],dir+filename)
