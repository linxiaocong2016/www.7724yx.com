    <?php foreach($list as $k=>$v):?>
        <li><a href="<?php echo $this->createUrl('index/zhuantidetail',array('id'=>$v[id]));?>">
        <img src="<?php echo $this->getPic($v['img'])?>" />
        <p><?php echo $v['name']?></p></a></li>
    <?php endforeach;?>