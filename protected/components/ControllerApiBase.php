<?php
/**
 * api接口基类
 * 
 * @author zhoushen
 */
class ControllerApiBase  extends CController{

	/**
	 * 接口返回数据
	 * @param  [type] $code [description]
	 * @param  [type] $msg  [description]
	 * @param  array  $data [description]
	 * @return [type]       [description]
	 */
	public function response($code, $msg, array $data = array())
	{
		echo json_encode(array(
				'code' => $code,
				'msg'  => $msg,
				'data' => $data,
			));
		die;
	}

	    /**
     * 修复java上传上来base64的问题
     * @return [type] [description]
     */
    protected function javaBase64Fix($baseString)
    {
        return str_replace(' ', '+', $baseString);
    }

    /**
     * 解密客户端上传加密信息
     * @param  [type] $input [description]
     * @return [type]        [description]
     */
    protected function parseEncryptionInfo($input)
    {
        $encryedAesKey = $this->javaBase64Fix($input['key']); //aes密钥
        $encryedData   = $this->javaBase64Fix($input['data']); //aes加密体数据

        $aeskey = RSA::privDecrypt($encryedAesKey);

        if(!$aeskey){
            throw new Exception('无法解析rsa信息', -1);
        }
        $data  = AESClient::decrypt($encryedData, $aeskey);
    
        $data = json_decode($data, true);

        if(!is_array($data)){
            throw new Exception('无法解析aes信息', -2);
        }    
        return $data;
    }

}