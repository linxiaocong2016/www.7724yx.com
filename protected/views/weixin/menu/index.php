
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
        <td>序号</td>
        <td>菜单标题</td>
        <td>链接</td>
        <td align="center">子集</td>
        <td align="center">修改</td>
    </tr>
    </thead>
    <tbody>
    <?php foreach($parents as $parent) :?>
        <tr>
            <td><?php echo $parent->parent_id ?></td>
            <td><?php echo $parent->title ?></td>
            <td><?php echo $parent->url ?></td>
            <td><?php echo 1 == $parent->have ? '有' : '没有'?></td>
            <td><?php echo CHtml::link('修改', '/weixin/menu/update/id/' . $parent->parent_id ) ?></td>
        </tr>
    <?php endforeach?>
    </tbody>
</table>
</body>
</html>