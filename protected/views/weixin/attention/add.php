
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
<!-- 7724.com Baidu tongji analytics -->
<script>
var _hmt = _hmt || [];
(function() {
var hm = document.createElement("script");
hm.src = "//hm.baidu.com/hm.js?d44b67217b90bf2331d5c7cf55365d0a";
var s = document.getElementsByTagName("script")[0];
s.parentNode.insertBefore(hm, s);
})();
</script>
</head>
<body>
    
    <table style="border: 1px;">
        <?php $form =$this ->beginWidget('CActiveForm');?>
        <tr style="font-weight: bold;">
            <td align="center"><h1>图文推送：</h1></td>
        </tr>
        <tr style="border: 1px;">
            <td>图文链接</td>
            <td><input type="text" value="" name="url"></td>
        </tr>
        <tr style="border: 1px;">
            <td>内容</td>
            <td><textarea rows="10" cols="30" name="content" ></textarea></td>
        </tr>
        <tr style="border: 1px;">
            <td>描述</td>
            <td><input type="text" value="" name="description"></td>
        </tr>
        <tr style="border: 1px;">
            <td>图片地址</td>
            <td><input type="text" value="" name="pic_url"></td>
        </tr>
        <tr style="border: 1px;">
            <td colspan="2" align="center">
                <input type="submit" value="增加">
                <input type="submit" value="取消">
            </td>
        </tr>
        <?php $this->endWidget();?> 
</table>
</body>
