
<?php foreach( $list as $key => $value ) {
    
    
    ?>
    <li>
        <a href="<?php echo $this->createURL("index/news", array( "id" => $value['id'] )); ?>" class="a1"><img src="<?php echo $value['image']; ?>"></a>
        <a href="<?php echo $this->createURL("index/news", array( "id" => $value['id'] )); ?>" class="a2"><?php echo $value['title']; ?></a>
        <p class="p1"><?php echo date("Y-m-d", $value['createtime']); ?></p>
    </li>
<?php } ?>   