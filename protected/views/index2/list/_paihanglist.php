<?php foreach( $lvInfo['paihang'] as $key => $value ) { ?>
    <li class="">
        <p class="p1"><?php echo ($_REQUEST['pageindex']-1)*$this->lvListPageSize+$key+1;?></p>
        <p class="p2"><img src="<?php echo empty($value['head_img']) ? "/img/default_pic.png" : "http://img.pipaw.net/" . $value['head_img']; ?>"></p>
        <p class="p3"><?php echo $value['nickname']; ?></p>
        <p class="p4"><?php echo date('Y-m-d H:i:s', $value['modifytime']); ?></p>
        <p class="p5"><b><?php
                                if(!$lvInfo['scoreformat'])
                                    echo ($value['score']*1) . $lvInfo['scoreunit'];
                                else
                                    echo $lvBLL->getScoreString($value['score'], $lvInfo['scoreformat']);
                                ?></b><span><?php echo $value['city']; ?></span></p>
    </li>
<?php } ?>