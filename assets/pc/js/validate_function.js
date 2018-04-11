
/**
 * 检查字符串长度
 * @param len_1  最小长度
 * @param len_2  最大长度
 * @param str_len 字符串
 * @returns {Boolean} 符合返回true，否则false
 */
function checkLength(len_1,len_2,str_len){
	len_1=parseInt(len_1);
	len_2=parseInt(len_2);
	str_len=parseInt(str_len);
	
	if(str_len<=len_2 && str_len>=len_1){
		return true;
	}else{
		return false;
	}
	
}

/**
 * 检查手机号码
 * @param mobile
 * @returns {Boolean} 合法返回true,否则false
 */
function checkMobile(mobile){
    var my = false;
    var partten = /^((\(\d{3}\))|(\d{3}\-))?1[0-9]\d{9}$/;
    if (partten.test(mobile))
        my = true;
    if (mobile.length != 11)
        my = false;
    if (!my) {
        return false;
    }
    return true;
}

/**
 * 检查是否 为字母或者数字组成
 * @param s
 * @returns
 */
function checkLetterOrNum(s){
	 var regex=/^[0-9A-Za-z_]{6,15}$/;
	 return regex.exec(s)
	 
}
