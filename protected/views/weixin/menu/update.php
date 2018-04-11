
<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
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
    <table border="1">
        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'form',
            'enableAjaxValidation' => false,
            'action' => $this->createUrl("/weixin/menu/update/id/$parents->parent_id"),
            'htmlOptions' => array('enctype' => 'multipart/form-data')
        ));
        ?>
        <thead>
        <?php
            if($childrenNum <=4){
                echo "<a href="."/weixin/menu/add".">增加子菜单</a>";
            }  else {
                echo "子菜单最多5个";
            }
        ?>
            <tr>
                <td>序号</td>
                <td>
                    <input type="text"  name="id" readonly="true" value="<?php echo $parents->parent_id; ?>"/>
                </td>
            </tr>
            <tr>
                <td>标题</td>
                <td>
                    <input type="text" name="title" value="<?php echo $parents->title; ?>"/>
                </td>
            </tr>
            <tr>
                <td>URL</td>
                <td>
                    <input type="text" name="url" value="<?php echo $parents->url; ?>"/>
                </td>
            </tr>
        </thead>
        <tr>
            <td>子集</td>
            <td>
                <table border="1" width="100%" class="table_a">
                    
                    <tr>
                        <td>
                            <?php  echo CHtml::radioButtonList('have', $parents->have, Parents::haveList(), array('separator' => '')) ?>
                        </td>
                    </tr>
                    <?php foreach ($childrens as $children) : ?>
                        <tr>
                            <td><input type="text" name="children[<?php echo $children->children_id; ?>][children_id]"
                                       value="<?php echo $children->children_id; ?>"/></td>
                            <td>
                                <input type="text" name="children[<?php echo $children->children_id; ?>][title]"
                                       value="<?php echo $children->title; ?>"/>
                            </td>
                            <td>
                                <input type="text" name="children[<?php echo $children->children_id; ?>][url]"
                                       value="<?php echo $children->url; ?>"/>
                            </td>
                            <td>
                                <a href="/weixin/menu/del/children_id/<?php echo $children->children_id;?>">删除</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="2" align="center">
                <input type="submit" value="修改">
                <input type="submit" value="取消">
            </td>
        </tr>
        <?php $this->endWidget(); ?>
    </table>
</body>
</html>