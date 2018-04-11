
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
    <?php
        if($pic_num>9){
            echo "图文提送最多10条";
        }else{
            echo"<a href='/weixin/attention/add'>增加推送图文</a>";
        }
    ?>
    <table style="border: 1px;">
        <?php $form =$this ->beginWidget('CActiveForm');?>
        <tr style="font-weight: bold;">
            <td align="center">图文链接</td>
            <td align="center">内容</td>
            <td align="center">图片描述</td>
            <td align="center">图片地址</td>
            <td align="center">删除</td>
        </tr>
        <?php foreach ($pictures as $picture ):?>
        
        <tr style="border: 1px;">
            
            <td><input type="text" value="<?php echo $picture->url; ?>" name="picture[<?php echo $picture->picture_id; ?>][url]"></td>
            <td><input type="text" value="<?php echo $picture->content;?>" name="picture[<?php echo $picture->picture_id; ?>][content]"></td>
            <td><input type="text" value="<?php echo $picture->description;?>" name="picture[<?php echo $picture->picture_id; ?>][description]"></td>
            <td><input type="text" value="<?php echo $picture->pic_url;?>" name="picture[<?php echo $picture->picture_id; ?>][pic_url]"></td>
            <td><a href="/weixin/attention/del/picture_id/<?php echo $picture->picture_id ?>">删除</a></td>
        </tr>
        <?php endforeach;?>
        <tr style="border: 1px;">
            <td colspan="2" align="center">
                <input type="submit" value="修改">
                <input type="submit" value="取消">
            </td>
        </tr>
        <?php $this->endWidget();?> 
</table>
</body>
</html>