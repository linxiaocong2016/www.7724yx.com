<?php

class ApiController extends Controller
{
	public function actionGetGameInfo() {
		$cgame_id=(int)$_REQUEST['cgame_id'];
		$ctype=(int)$_REQUEST['ctype'];
		$gameInfo=Gamefun::getInfo($cgame_id,$ctype);
		if($gameInfo)echo json_encode($gameInfo);
	}
}