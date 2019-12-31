<?php
function guid(){
    //修改自GUID生成，因为使用了{ - }这些特殊符号反而会让某些自动识别URL（比如QQ）中识别失败（读取不到最后的"}"）
    //所以就删掉了
    mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
    $charid = strtoupper(md5(uniqid(rand(), true)));
    $uuid =""
        .substr($charid, 0, 8)
        .substr($charid, 8, 4)
        .substr($charid,12, 4)
        .substr($charid,16, 4)
        .substr($charid,20,12);
    return $uuid;
}
$id=guid();
//字段转换成json
$json["id"]=$id;
$json["title"]=$_POST["title"];
$json["language"]=$_POST["language"];
$json["author"]=$_POST["author"];
$json["expiration"]=$_POST["expiration"];
$json["code"]=$_POST["code"];
$json["password"]=$_POST["password"];
$json["encoding"]=$_POST["encoding"];
date_default_timezone_set('Asia/Shanghai');
$json['time']= time();

//旧版接口时json，为了避免直接访问php文件，新版采用了与PrivateBin类似的思路，采用php文件存储json
//对于直接访问的返回http_response_code(403);
//paste页面采取了兼容的方式，优先考虑PHP扩展名存储
$file=fopen("data/".$id.".php","w");
fwrite($file,"<?php http_response_code(403); die(); ?>\n".json_encode($json));
    if($_POST["api"]=="python"){
        echo $id;
        //方便PythonClient的使用，对于指定API是python的只提供地址即可。
    }else{
        echo "<script>window.location='paste.php?id=".$id."'</script>;";
     }
?>
