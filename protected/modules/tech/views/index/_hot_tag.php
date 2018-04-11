<div class="biaoqian idx-biaoqian">
<?php
	//热门标签
	$arr=CommonFunction::hotTagInfo();
	//热门标签
	//$arr=array("苹果","谷歌","手游","移动IM","可穿戴","创业");
	$count=count($arr);
	for($i=0;$i<$count;$i++){
		if($arr[$i]=="")continue;
		$url=$this->createurl("index/list",array("keyword_s"=>$arr[$i]));
		echo "<a href='{$url}' target=_blank>{$arr[$i]}</a>";
		if($i<$count-1){
			echo "|";
		}
	} 
?>
</div>