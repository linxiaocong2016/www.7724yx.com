
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
<div style="font-size: 13px;margin: 10px 5px">
            <?php $form = $this ->beginWidget('CActiveForm');?>
            <table >
                    <?php echo $form->hiddenField($childrenModel,'children_id');?>
                <tr>
                    <td><?php echo $form ->labelEx($childrenModel,'parent_id');?></td>
                    <td> <?php echo $form->dropDownList($childrenModel,'parent_id',$parent_id) ; ?></td>
                </tr>
                <tr>
                    <td><?php echo $form ->labelEx($childrenModel,'title');?></td>
                    <td> <?php echo $form ->textField($childrenModel,'title');?></td>
                </tr>
                <tr>
                    <td><?php echo $form ->labelEx($childrenModel,'url')?></td>
                    <td> <?php echo $form ->textField($childrenModel,'url');?></td>
                   
                </tr>

                
                <tr>
                    <td >
                        <input type="submit" value="添加">
                    </td>
                </tr>  
            </table>
            <?php $this -> endWidget();?>
        </div>
</body>
</html>