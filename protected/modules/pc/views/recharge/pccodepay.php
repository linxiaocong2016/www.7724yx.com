<?php 
/**
 * pc微信扫码支付
 */

$codeImg = $reParams['data']['code_img'];
$fee     = $reParams['data']['total_fee'] / 100;
?>
<!DOCTYPE html>
 <html lang="en">
 <head>
 	<meta charset="UTF-8">
 	<title>7724-微信支付</title>
 	<style>
 		.back{
 			    cursor: pointer;
			    margin-top: 8px;
			    color: #717171;
			    border: 1px solid #e0e0e0;
			    width: 100%;
			    line-height: 32px;
			    float: left;
			    font-size: 14px;
			    color: #099cda;
			    border: 1px solid #099cda;
			    text-align: center;
			    text-decoration:none;
 		}

 	</style>
 </head>
 <body style="background-color: #eee;">
 		<div style="margin: 100px;position: absolute;width: 200px;">
	 		<img width="180px" src="/images/WePayLogo.png" alt="">
	 	</div>

 	<div class="" style="width: 260px;margin: 100px auto;">
 		<div style="font-weight: bold;margin: 10px 0;text-align: center;">
 			向7724支付 <?php echo $fee; ?> 元
 		</div>
	 	<div>
	 		<img width="260px" src="<?php echo $codeImg; ?>" alt="">
	 	</div>
	 	<div>
	 		<img src="/images/wepaywenzi.png" alt="">
	 	</div>
	 	<div style="color: #8d8d8d;font-size: 12px;margin: 10px 0;text-align: center;">
	 		<span>请不要重复支付，支付完成后点击返回游戏</span>
	 	</div>
	 	<div>
	 		<a href="javascript:history.go(-2);" class="back" >返回游戏</a>
	 	</div>
	 	<div style="color: #8d8d8d;font-size: 12px;margin: 65px 0;text-align: center;">
	 		注：充值遇问题，请联系客服QQ：2885339244
	 	</div>
 	</div>
 </body>
 </html>

