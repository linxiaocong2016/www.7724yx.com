  <?php
         $lvBLL = new Game();
        foreach( $list as $key => $value ) {
            ?>
            <dt id="dt<?php echo $value['game_id']; ?>">
            <em class="del"></em> <a href="<?php echo $this->getDetailUrl($value); ?>">
                <p class="p1">
                    <img src="<?php echo strpos($value['game_logo'], 'http://') !== FALSE ? $value['game_logo'] : 'http://img.pipaw.net/' . $value['game_logo']; ?>" />
                </p>
                <p class="p2">
                    <i><?php echo $value['game_name']; ?></i> <span>最好战绩：<?php
                                if(!$value['scoreformat'])
                                    echo $value['score']*1 . $value['scoreunit'];
                                else
                                    echo $lvBLL->getScoreString($value['score'], $value['scoreformat']);
                                ?></span>
                </p>
                <p class="p3">
                    <span>继续玩</span>
                </p>
            </a>
            </dt>
        <?php } ?>