<?php
/**
 * @author alun (http://alunblog.duapp.com)
 * @version 1.0
 * @created 2013-5-17
 */
 
class Rsa
{
private static $PRIVATE_KEY = '-----BEGIN PRIVATE KEY-----
MIICdgIBADANBgkqhkiG9w0BAQEFAASCAmAwggJcAgEAAoGBAMls4j16RIQQtFYx
4NuxuUcOb6HBK37pJyb2IQGr7PDaZgqGcT4//U7Ffl6zrQMkteyDpoiXoXt+eckb
OvqSQ2MMuy14lw93AMLSiSO5LQGAYdKPWwMPjMN43EwKpya8DDFkGBZ54qyBFy8x
XtGvBCz4n3CVM0F8MsM79d0svympAgMBAAECgYBNWxy0AY3orkWjyLoThXijWl7y
3y3mKoKXyvS4IJ/5i9aeei1pe8e7hctXcWejimi3sYO2d41T0SnSWzvWWW+5Hh/M
QwLlfgdBB0GjLbLbiXjEQ4oVUIBVG7e0yh8LaYBGwvs0702lBSBHVlwUZgcE77TP
M003VOQ2RD9JccI6lQJBAPwD2WyP2cxvTIgJHs19/BPRlQBAqUueMoc7mBZuoslv
BwzzJjp+Y+NnSdtQhSmKNY50IxXzwrP5bMs0VC12YbcCQQDMnD+gBKQwEX9BwZa7
blHL7QkP5dkiRhViEp2epEED8FAITBFuYxxVLFtLtRwYUxej17oAtA+4otekHXnk
d0+fAkAQs1SaPCIryQhiT3BqH7ovugjMvnw5lZ81lP5sJiLFhIUMF6Tl5XBLJpIf
ZYOdkBoieZHFp6S0dof+I1acuKabAkB4cvKxjInrEiHL396P792PIrbW+QPdvUwR
M8w9+4uaefljKQSJ6yZerYIBC1jCqQedl/0TNOycUKCJKCD9cY8ZAkEA8UFmJfwA
j92VuWVnkx+80vwbYWmE5rPK7suUSapgvFbQ8JPukoezWPS4pz/ZZ7vAtrYVWU95
ABYNVM8UdNvd+w==
-----END PRIVATE KEY-----';

    /**
    *返回对应的私钥
    */
    private static function getPrivateKey(){
    
        $privKey = self::$PRIVATE_KEY;
         
        return openssl_pkey_get_private($privKey);     
    }
 
    /**
     * 私钥加密
     */
    public static function privEncrypt($data)
    {
        if(!is_string($data)){
                return null;
        }      
        return openssl_private_encrypt($data,$encrypted,self::getPrivateKey())? base64_encode($encrypted) : null;
    }
    
    
    /**
     * 私钥解密
     */
    public static function privDecrypt($encrypted)
    {
        if(!is_string($encrypted)){
                return null;
        }
        return (openssl_private_decrypt(base64_decode($encrypted), $decrypted, self::getPrivateKey()))? $decrypted : null;
    }
}
?>