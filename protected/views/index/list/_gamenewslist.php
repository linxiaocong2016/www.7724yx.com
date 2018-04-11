
<?php foreach( $list as $key => $value ) { ?>
    <li>
        <?php
        if($value['gameid'])
            $url = "/" . $this->getGamePinYin($value['gameid']) . "/" . ($value['type'] == "1" ? "news" : "gonglue") . "/" . $value['id'] . ".html";
        else
            $url =  "/" . ($value['type'] == "1" ? "news" : "gonglue") . "/" . $value['id'] . ".html";
        ?>         
        <a href="<?php echo $url; ?>" class="a1"><img src="<?php
                if(!$value['image']){
                   echo  "/img/" .( $type == 1 ? "default_new.jpg" : "default_gl.jpg"); 
                }
                else {
                    $lvImg=$value['image'];
                    if(strpos( $lvImg,'http://')!==FALSE)
                    {
                        $lvImg=str_replace("pipaw.net", "7724.com", $value['image']);
                    }
                    else {
                        $lvImg='http://img.7724.com/'.$lvImg;
                    }
                    echo $lvImg;
                }
                //echo $value['image'] ? str_replace("pipaw.net", "7724.com", $value['image'])  : ($type == 1 ? "default_new.jpg" : "default_gl.jpg"); 
                
                ?>"></a>
        <a href="<?php echo $url; ?>" class="a2"><?php echo $value['title']; ?></a>
        <p class="p1"><?php echo date("Y-m-d", $value['publictime']); ?></p>
    </li>
<?php } ?>   