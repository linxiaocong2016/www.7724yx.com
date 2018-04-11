
<script>
    var allGame =<?php echo json_encode(GameBLL::getAllGame()) ?>;
    function getGameName() {
        var game_id = $("#game_id").val();

        var game_name = allGame[game_id];
        if (!game_name) {
            game_name = ''
        }
        $("#gamename").val(game_name);
        $("#game_name").val(game_name);
    }
    function getGameID() {
        $("#game_id").val("");
        $("#gamename").val("");
        var gamename = $("#game_name").val();
        if (!gamename)
            return;
        var size = 0, key;
        for (key in allGame) {
            if (allGame[key].indexOf(gamename) >= 0) {
                if ($("#gamename").val().length > 0) {
                    $("#game_id").val('');
                    $("#gamename").val($("#gamename").val() + "," + allGame[key]);
                }
                else
                {
                    $("#game_id").val(key);
                    $("#gamename").val(allGame[key]);
                }
            }

        }
    }    
</script>


<form method="POST" >
	<input type="hidden" name="id" value="<?php echo $info['id']?>">
		<table border="0" class="admintable">
			<tr>
				<td>
					<input type="button" value="返回" onclick="window.history.go(-1);" />
				</td>
			</tr>
		</table>
		
		<div style="height: 10px; overflow: hidden;">&nbsp;</div>
		<table border="1" style="width: 60%">
			<tr>
				<td>ID：</td>
				<td><?php echo $info['id']?></td>
			</tr>			
			<tr>
				<td>游戏ID：</td>
				<td>
					<input id="game_id" type="text" name="game_id" value="<?php echo $info['game_id'] ?>" onkeyup="getGameName()" onchange="getGameName()">
                             
				</td>
			</tr>			
			<tr>
        		<td>游戏名称：</td>
        		<td>
        		<input id="game_name" type="text" name="game_name"  onkeyup="getGameID()" onchange="getGameID()"> 
                <input id="gamename" type="text" name="gamename" style="background-color: #c0c0c0;width: 350px;" value="<?php echo $info['game_name'] ?>"> 
        		
        		</td>
    		</tr>	
    		<tr>
				<td>排序</td>
				<td>
					<?php echo Helper::getInputText("order_desc",$info['order_desc'],array('width'=>'120px'));?>
					【值越大，显示越靠前】
				</td>
			</tr>		
				
			<tr>
				<td></td>
				<td  style="padding: 10px 0 10px 10px">
					<?php echo CHtml::submitButton('提交',array('onclick'=>"return checkForm()")); ?>
					<input type="button" value="返回" onclick="window.history.go(-1);" />
				</td>
			</tr>
		</table>
		<div id="tishi_msg" style="color:red;margin:10px 10px"></div>		
</form>

<script type="text/javascript">
					
function checkForm(){

	var game_id = $('#game_id').val();
	if ($.trim(game_id) == '') {
		$('#tishi_msg').text('请确定 游戏ID');
		return false;
	}
	
	var reg_zs = /^([1-9][0-9]*|0)$/i;
	var order_desc = $('input[name="order_desc"]').val();	
	if (!reg_zs.test(order_desc)) {		
		$('#tishi_msg').text('排序值须 >=0 的整数');
    	return false;	
	}
	
}
</script>