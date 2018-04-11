<?php
$c = Yii::app()->controller->id;
$getArr = $_GET;
unset($getArr['id']);
?>
<script>


</script>
<style>
    .admintable{width:60%}
</style>
<form method="get">
    <table border="0" class="admintable">
        <tr>
            <td>
                搜索：
                标题：
                <input type="text" name="title_s" class="contentinput" value="<?php echo $_GET['title_s'] ?>" />
                <input type="submit" value="查询" id="" name="" class="" />
            </td>
        </tr>
    </table>
    <table border="1" class="admintable table">
        <tr> 
            <th  >UID</th>
            <th  >用户手机</th>
            <th >收藏数量</th>
            <th>操作</th>
        </tr>
        <?php if(isset($list) && $list): ?>
            <?php foreach( $list as $k => $v ): ?>
                <tr> 
                    <td><?php echo $v['uid'] ?></td>
                    <td><?php echo $v['username'] ?></td>
                    <td><?php echo $v['counta'] ?></td> 
                    <td>
                        <?php $getArr['id'] = $v['id']; ?>
                        <a href="<?php echo $this->createUrl("{$c}/look", array( "uid" => $v['uid'] )); ?>">查看</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>			 
        <tr>
            <td colspan="10">
                <div class="pagin">
                    <?php
                    $this->widget('CLinkPager', array(
                        'firstPageLabel' => '首页',
                        'lastPageLabel' => '末页',
                        'prevPageLabel' => '&lt;&lt;',
                        'nextPageLabel' => '&gt;&gt;',
                        'maxButtonCount' => 12,
                        'pages' => $pages ));
                    ?>
                </div>
            </td>
        </tr>
    </table>
</form>