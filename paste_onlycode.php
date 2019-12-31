<?php
    $json = file_get_contents("data/".$_GET["id"].".json");
    $arr = json_decode($json,true);
    date_default_timezone_set('Asia/Shanghai');//时区设置
    $tm=date("Y-m-d H:i:s",$arr['time']);
    if($arr["language"]=="" && $arr["style"]!=""){
        $arr["language"]=$arr["style"];
    }
    //文件信息
    $before="作者:".$arr["author"]."\A 语言:".$arr["language"]."\A 时间:".$tm;
    if($arr["password"]!=""){
        // if($_SERVER['REQUEST_METHOD']=="GET"){
        // 	if($_GET["password"]!=$arr["password"]){
        //         include "needpassword.php";
        //         echo "需要密码";
        //     	die();
        // 	}
        // }
        if ($_SERVER['REQUEST_METHOD']=="POST") {
            if($_POST["password"]!=$arr["password"]){
                echo "1Need password or password wrong!";
                die();
            }
        }else{
            echo "1Need password or password wrong!";
            die();
        }
    }
    if($arr["expiration"]=="NEVER" || $arr["expiration"]==-1){
        $before=$before."永久有效";
    }else{
        if(time()-$arr["time"]>$arr["expiration"]){
            echo "3Expired Paste!";
            die();
        }
    }
    echo "0";
    include "data/".$_GET["id"].".json";
?>