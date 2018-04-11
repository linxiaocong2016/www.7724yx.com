
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


            <?php $form = $this->beginWidget('CActiveForm'); ?>
            <tr>
                <td>关键字</td>
                <td><input type="text" name="keyword" value=""></td>
            </tr>
            <tr>
                <td>模式</td>
                <td>
                    <input type="radio" name="classify" checked="checked" >文字
                    <input type="radio" name="classify" >图文
                </td>
            </tr>
            <tr>
                <td>内容/标题</td>
                <td><input type="text" name="content" value="" ></td>
            </tr>
            <tr>
                <td>描述(图文)</td>
                <td><input type="text" name="description" value="" ></td>
            </tr>
            <tr>
                <td>图片地址</td>
                <td><input type="text" name="pic_url" value="" ></td>
            </tr>
            <tr>
                <td>超链(图文)</td>
                <td><input type="text" name="url" value="" ></td>
            </tr>
            <tr>
                <td colspan="2" align="center">
                    <input type="submit" value="增加">
                    <input type="submit" value="取消">
                </td>
            </tr>
            <?php $this->endWidget(); ?> 
        </table>
    </body>
</html>
