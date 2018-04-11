<?php
$c = Yii::app()->controller->id;
$getArr = $_GET;
unset($getArr['id']);
?>
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
            <th width="15%">游戏ID</th>
            <th width="25%">游戏名称</th>
            <th width="10%">收藏次数</th>
            <th width="10%">明细</th>
        </tr>
        <?php if(isset($list) && $list): ?>
            <?php foreach( $list as $k => $v ): ?>
                <tr>
                    <td><?php echo $v['game_id'] ?></td>
                    <td><?php echo $v['game_name'] ?></td>
                    <td><?php echo $v['num'] ?></td>
                    <td>  
                        <a href="<?php echo $this->createUrl("/xmsb/usercollectgame/index", array( "title_s" => $v['game_name'] )); ?>">明细</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        <tr>
            <td colspan="7">
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