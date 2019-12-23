<?php
    function needpassword(){
        switch ($_GET["access"]) {
            case 'api':
                echo "1请输入密码";
            break;
            case 'default':
            default:
                include "needpassword.html";
                break;
        }
        die();
    }
    function outoftime(){
        switch($_GET["access"]){
            case 'api':
                echo "3文件不存在或者已经过期";
                break;
            case 'default':
            default:
                header("HTTP/1.0 404 Not Found");
                echo "文件不存在或者已经过期";
            // http_response_code(404);
                die();
                break;
        }
    }
    if(file_exists("data/".$_GET["id"].".php")){
        $file=fopen("data/".$_GET["id"].".php","r");
        $json=fgets($file);
        $json=fgets($file);
    }else{
        //采用了的PrivateBin思路。对于直接访问返回403
        if(file_exists("data/".$_GET["id"].".json")){
            $json = file_get_contents("data/".$_GET["id"].".json");
        }else{
            outoftime();
        }
    }
    $arr = json_decode($json,true);
    date_default_timezone_set('Asia/Shanghai');//时区设置
    $tm=date("Y-m-d H:i:s",$arr['time']);
    if($arr["language"]=="" && $arr["style"]!=""){
        $arr["language"]=$arr["style"];
    }
    $before="作者:".$arr["author"]."\A 语言:".$arr["language"]."\A 时间:".$tm;
    $before=$before."(";
    if($arr["expiration"]=="NEVER" || $arr["expiration"]==-1){
        $before=$before."永久有效";
    }else{
        if(time()-$arr["time"]>$arr["expiration"]){
            outoftime();
        }else{
            $dtime=$arr["expiration"]-(time()-$arr["time"]);
            if($dtime<180){
                $before=$before.$dtime."秒后过期";
            }else{
                if($dtime<5400){
                    $before=$before.ceil(($dtime/60))."分后过期";
                }else{
                    if($dtime<86400){
                        $before=$before.round(($dtime/3600),1)."小时后过期";
                    }else{
                        $before=$before.round(($dtime/86400),1)."天后过期";
                    }
                }
            }
        }
    }
    $before=$before.")";
    //文件信息
    if($arr["password"]!=""){
        //不允许get协议，支持get的话相当于没有。。。反正都是写到了URL上
        if ($_SERVER['REQUEST_METHOD']=="POST") {
            if($_POST["password"]!=$arr["password"]){
                needpassword();
                die();
            }
        }else{
            needpassword();
        }
    }
    if($_GET["access"]=="api"){
        echo "0".$json;
        die();
    }
    ?>
<!DOCTYPE html>
<html>
<style>
    body,html,pre,code {
        font-family: "consolas";
        margin: 0 0 0 0;
        height:100%;
        /* overflow: hidden hidden; */
    }
    body,html,pre{
        overflow: hidden hidden;
    }
    pre{
        font-size: larger; 
        tab-size:4;
    }
    code{
        white-space: pre-wrap;
        word-wrap: break-word;
        line-height:14pt;
        font-size:9pt;
    }
    ol{
        margin-left: 1em;
        margin-top: 0;
        margin-right:5px;
    }
    code:before{
        content:'<?php echo $before ?>';
    }
</style>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php if($arr["title"]!=""){echo "CodePaste - ".$arr["title"];}else{echo "CodePaste - "."未命名";}?></title>
</head>

<body>
    <?php
        //输出正文
        echo "<pre><code id='cd' class='".$arr["language"]."'>";//style是语言
        echo $arr["code"];
        echo "</code></pre>";
    ?>
    <link rel="stylesheet" href="src/styles/monokai-sublime.css">
    <script src="src/highlight.pack.js"></script>
    <script>
        hljs.initHighlightingOnLoad();
        var e = document.querySelectorAll("code");
        var e_len = e.length;
        var i;
        for (i = 0; i < e_len; i++) {
            e[i].innerHTML = "<ol><li>" + e[i].innerHTML.replace(/\n/g, "\n</li><li>") + "\n</li></ul>";
        }
    </script>
</body>
</html>