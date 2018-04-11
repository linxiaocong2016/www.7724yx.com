
<div style="padding:15px;">
	<div style="margin-bottom:10px;">	
		<input type="button" value="返回" style="cursor: pointer;" onclick="window.history.go(-1);" />
	</div>
	
	<div style="margin-bottom:10px;">	
		未入库游戏：<span id="no_import_game" style="color: red"><?php echo $countVal?></span> 款
	</div>		    
	    
	<div style="margin-bottom: 10px;">
	    使用的条件：<span style="color:#2366A8">只导入从未导过的游戏，每次导入最多20条</span>
	</div>
			
	<div style="margin-bottom: 10px;">
	    导入结果：
	    <span id="import_result">
	    	<?php if(isset($importCountErr)):?>
	    		<?php if($importCountErr>0):?>
			    	<font color="red"><?php echo $importCountErr;?> 款游戏导入失败 </font>
			    	<br>
			    	<font color="red"><?php echo $import_fail_game;?></font>
	    		<?php else:?>
	    			<?php if($import_success_game):?>
		    		<font color="green">导入成功</font>
		    		<br>
			    	<font color="green"><?php echo $import_success_game;?></font>
			    	<?php endif;?>
		    	<?php endif;?>
	    	<?php endif;?>
	    </span>
	</div>	
			
	<div style="margin-bottom: 10px;">
	    更新结果：
	    <span>
	    <?php if(isset($updCountErr)):?>
	    	<?php if($updCountErr>0):?>
		    	<font color="red"><?php echo $updCountErr;?> 款游戏更新失败 </font>
		    	<br>
		    	<font color="red"><?php echo $upd_fail_game;?></font>
	    	<?php else:?>
	    		<?php if($upd_success_game):?>
		    	<font color="green">更新成功</font>
		    	<br>
			    <font color="green"><?php echo $upd_success_game;?></font>
			    <?php endif;?>
		    <?php endif;?>
	    <?php endif;?>
	    </span>
	</div>	
	
	<form action="<?php echo $this->createUrl(Yii::app()->controller->id."/gameimportpipaw")?>" 
		method="post">	
		<!-- 隐藏域无用 -->
		<input type="hidden" name="import_flag" value="1">	
	   	<div style="margin-bottom: 10px;">
	   		<?php if($countVal>0):?>
		    <input type="submit" value="导入" style="cursor: pointer;" />
		    <?php endif;?>
		</div>		
	</form>
   
</div>
