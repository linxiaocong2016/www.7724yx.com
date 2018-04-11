<?php 
$this->ad_log();
$tag_flag = $_GET['flag'];

$channel_flag=1; 
if ($tag_flag) {
    $sql = "select channel_flag from ext_sdk_member where tg_flag like :tg_flag ";
    $cmd = Yii::app()->ucdb->createCommand($sql);
    $cmd->bindValue(":tg_flag", "%#$tag_flag#%");
    $channel_flag = $cmd->queryScalar();
}

?>
<script type="text/javascript" src="/js/header_login_cookie.js"></script>
<script type="text/javascript" >
function loginDirect(){
	<?php $user_agent = $_SERVER['HTTP_USER_AGENT'];
 	if (stripos($user_agent, 'MicroMessenger')):?>
		<?php if(!isset($_SESSION ['userinfo']) || empty($_SESSION ['userinfo'])):?>
			if ( $("#game_id_tourl").length > 0 ) {
				window.location.href="/user/login?url=/online/"+$('#game_id_tourl').val();
			}else{
				window.location.href="/user/index";
			}
		<?php else:?>
			window.location.href="/user/index";
		<?php endif;?>			 	 	
 	<?php else:?>
 	window.location.href="/user/index"; 	
 	<?php endif;?>	
}
//setTimeout("$('#show_search_u_di').show()",3000);//3秒在显示搜索图片，防止和内容重叠
</script>


<?php 
if($channel_flag==1)
{
?>

<header class="head clearfix">
     <div class="logo"><a href="http://www.7724.com/" ><img src="/img/new_logo.png" alt="7724游戏" title="7724游戏" /></a></div>
     <div class="search">
       <a id="head_user_login" href="javascript:void(0)" onclick="loginDirect()"  class="a1">个人中心</a>
       <a href="<?php echo Tools::absolutePath('/search')?>" class="a2">搜索</a>
     </div>
</header>


<!-- 
<header class="head clearfix">
    <div class="logo"><a href="/"><img src="/assets/index/img/logo.png" alt="7724游戏" title="7724游戏" /></a></div>
    <div class="search" style="float:left;margin-left:10px;margin-right: 0px;width:30%">
        <form id="search_form_id" action="<?php echo $this->createUrl('index/search'); ?>"  onSubmit="return searchnamesend()">
            <?php
            $data = Gamefun::allGameSearchStatus(3);
            $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
                'name' => 'keyword',
                'source' => array_values($data), 'skin' => false,
                'options' => array( 'autoFocus' => '1',
                    'minLength' => '1',
                ), 'htmlOptions' => array(
                    'class' => 'search_tx',
                    'placeholder' => '请输入游戏名称'
                ),
            ));
			
            ?>
            <input id="show_search_u_di" type="submit" class="search_bt" value="" style="display: none;"/>
        </form> 
    </div>
    <div style="float:left;width:30px; height:35px; position:relative;">
        <em class="red_click"></em>
        <a id="head_user_login" href="javascript:void(0)" onclick="loginDirect()" style="margin-right:4px; width:35px; height:35px; display:block;background:url(/img/ico_13.png) no-repeat center center; background-size:20px 20px;"></a>  
    </div>
</header>
-->
<?php 
}
?>