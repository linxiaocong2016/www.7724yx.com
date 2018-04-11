
<?php
//微信提供的AppID和AppSecret
$appid = "wxdcd239e365944acc";
$appsecret = "3a2c2a7a9b70200ca628f40263291343";

foreach($parents as $parent){
    $parentId = $parent['parent_id'];
    foreach($childrenModel->getChildrenByID($parentId) as $index => $children){
        $sub_button[$index] = array(
            'type' => 'view',
            'name' => "$children->title",
            'url' => "$children->url"
        );
    }
    $menus['button'][] = array(
        'name' => $parent['title'],
        'sub_button' => $sub_button
    );
}
$menus = json_encode($menus, JSON_UNESCAPED_UNICODE);

$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret";

$ch = curl_init(); 

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$output = curl_exec($ch);
curl_close($ch);
$jsoninfo = json_decode($output, true);
$access_token = $jsoninfo["access_token"];


$url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$access_token;
$result = https_request($url, $menus);

$result = json_decode($result);

$errcode = $result->errcode;
//print_r($errcode);

//exit;
function https_request($url,$data = null){
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    if (!empty($data)){
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    }
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($curl);
    curl_close($curl);
    return $output;
}
if($errcode == 0){
    header("http://www.7724.com/weixin/menu/");
}else{
    echo $errcode;
    echo "修改失败";
}

?>