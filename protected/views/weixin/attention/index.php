
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
<table class="table_a" border="1" width="100%">
    <thead>
    <tr style="font-weight: bold;">
        <td>类型</td>
        <td>内容</td>
        <td>启用</td>
        <td align="center">操作</td>
    </tr>
    </thead>
    <tbody>
        <tr>
            <td>文字链接</td>
            <td><?php echo $text->content ?></td>
            <td><?php if($text->use ==1){ echo "是";} else { echo "否"; }?></td>
            <td><a href="/weixin/attention/text">编辑</a></td>
        </tr>
        <tr>
            <td>图文链接</td>
            <td><?php echo $picture->content ?></td>
            <td>请修改文字推送</td>
            <td><a href="/weixin/attention/picture">编辑</a></td>
        </tr>
    </tbody>
</table>
</body>
</html>  