<?php
/**
 * 常规的检测函数
 *
 * @date 2017-5-11
 * @author crh
 */
class VerifyData
{
    /**
     * 是否符合的中国身份证号
     * 身份证15位编码规则：dddddd yymmdd xx p
     * dddddd：6位地区编码
     * yymmdd: 出生年(两位年)月日，如：910215
     * xx: 顺序编码，系统产生，无法确定
     * p: 性别，奇数为男，偶数为女
     *
     * 身份证18位编码规则：dddddd yyyymmdd xxx y
     * dddddd：6位地区编码
     * yyyymmdd: 出生年(四位年)月日，如：19910215
     * xxx：顺序编码，系统产生，无法确定，奇数为男，偶数为女
     * y: 校验码，该位数值可通过前17位计算获得
     *
     * 前17位号码加权因子为 Wi = [ 7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2 ]
     * 验证位 Y = [ 1, 0, 10, 9, 8, 7, 6, 5, 4, 3, 2 ]
     * 如果验证码恰好是10，为了保证身份证是十八位，那么第十八位将用X来代替
     * 校验位计算公式：Y_P = mod( ∑(Ai×Wi),11 )
     * i为身份证号码1...17 位; Y_P为校验码Y所在校验码数组位置
     * @param string $identityno
     */
    public static function isIdentityno($identityno)
    {
        // 15位和18位身份证号码的正则表达式
        $pattern = '/^(^[1-9]\d{7}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}$)|(^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])((\d{4})|\d{3}[Xx])$)$/';

        // 如果通过该验证，说明身份证格式正确，但准确性还需计算
        if (preg_match($pattern, $identityno)) {
            $identitynoLen = strlen($identityno);
            if ($identitynoLen==18) {
                $idCardWi = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2); // 将前17位加权因子保存在数组里
                $idCardY = array(1, 0, 10, 9, 8, 7, 6, 5, 4, 3, 2); // 这是除以11后，可能产生的11位余数、验证码，也保存成数组
                $idCardWiSum = 0; //用来保存前17位各自乖以加权因子后的总和
                for ($i=0; $i<17; $i++) {
                    $idCardWiSum += substr($identityno, $i, 1)*$idCardWi[$i];
                }

                $idCardMod = $idCardWiSum % 11; // 计算出校验码所在数组的位置
                $idCardLast = substr($identityno, -1); // 得到最后一位身份证号码

                // 如果等于2，则说明校验码是10，身份证号码最后一位应该是X
                if ($idCardMod==2) {
                    if ($idCardLast=='X' || $idCardLast=='x') {
                        return TRUE;
                    }
                } else {
                    // 用计算出的验证码与最后一位身份证号码匹配，如果一致，说明通过，否则是无效的身份证号码
                    if ($idCardLast==$idCardY[$idCardMod]) {
                        return TRUE;
                    } else {
                        return FALSE;
                    }
                }
            }
            return TRUE;
        }

        return FALSE;
    }

    /*
     * 判断是否中文（utf-8格式）
     *
     * @param string $str
     */
    public static function isOnlyChinese ($str)
    {
        $pattern = '/^[\x7f-\xff]+$/';
        if (preg_match($pattern, $str)) {
            return TRUE;
        }
        return FALSE;
    }
}