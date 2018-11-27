<div class="general_box">
    <div class="general">
    <!--面包屑-->
    <div class="h5_local">
       <em class="local_home">当前位置：</em>
       <a href="/">首页</a><span>&gt;</span>
       <?php if($lvGameInfo['wy_dj_flag']==1):?>
       <a href="/new.html">单机</a><span>&gt;</span>
       <?php else:?>
       <a href="/wy.html">手游</a><span>&gt;</span>
       <?php endif;?>
       <em><?php echo $lvGameInfo['game_name']?></em>       
    </div>
    
    <div class="detail_box">
        <div class="detail_msg_box" style="position:relative;width:1092px;height:179px;margin:42px auto;border-bottom: 1px solid #ccc;">
            <div class="detail_img">
                <img style="width: 120px;height: 120px;" src="<?php echo $data['img'];?>" >
            </div>
            <div class="detail_msg" >
                <h1><?php echo $data['name'];?></h1>
                <p>
                    <span class="msg_item">版本：</span>通用
                    <span class="msg_item">平台：</span><img src="/img/android_icon.png">     
                    <span class="msg_item">大小：</span><?php echo $data['size'];?>M    
                    <span class="msg_item">下载量：</span><?php echo $data['downnum'];?>
                </p>
            </div>
            <div class="detail_dl">
                <a href="<?php echo $data['downurl'];?>"><img src="/img/download_1.png"></a>
            </div>
        </div>
        <div class="detail_intro">
            <h1>游戏简介：</h1>
            <span><?php echo $data['desc'];?></span>
        </div>
    </div>   
</div> 
</div>

