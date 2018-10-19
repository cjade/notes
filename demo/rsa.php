<?php
/**
 * Created by PhpStorm.
 * User: haibao
 * Date: 2018/10/8
 * Time: 上午9:29
 */

class Rsa{
    static private $_instance;

    static public $privateKey = '-----BEGIN RSA PRIVATE KEY-----
        MIICXgIBAAKBgQC6fsWwsoYDwU1bnl65c1nWZyftQ8E50PvGQ0aAwchyLYKnEmRE
        SZNS8/vy8VC7gwpvK1CxSM+91SxOhG13SaxkPUct58BjwjrGFttWaV2NGMfAG4YD
        BvVSG7RJ6VJCT7AA0OK7/n782H/KWBTeUuvhbs4x18lZM5kgozFE7U2KkQIDAQAB
        AoGAOwzO7a7pLiEzrFHN7mxuwqtxAfhVI0hfoBxHI5e4Lybn2pzBMLoXMsncOcVc
        6bKJSD/v0eKbHKF14PqfaojiU2SJRLa6DsjrguJOO++Cv7hj3pMJQW17w22SJc0C
        1st5sYRprrYJs/dVGPMoAwTUMoVhj1mEpJUzg6rO6Kl+k10CQQDfFxlCjHGy7QFY
        aS3PyvrX33w2Mstl4cTgK0AoB9Hf13qDcrgdJyLNzktwWn18VgI8wGKW5PkiPQc8
        6ODtSwcXAkEA1gGt1LPFzuCqKRVYoUly0Vnf9PhdHIpObzHM4i9cx9NUuEJRe1zJ
        UAy/SZ7jy3rM+rXTwWROYPrf98zOu5AElwJBAKcBXrqP8snUXO53hoEI3fHe7tiP
        ++4gLkb2ece92uJsJ93hcmz4kDhrV9QbGdeLd49NIkD91ndIInP5jfB1+y8CQQCi
        2Ng8OShWymLxrEqrQMbcQ7XGJZBE/YG933l2zG+fp1Kae+yhIPQXs/uDvCK/XgzI
        4r/mM7vo1D7Fw9W0qFy1AkEAj0muELc79zglnQqBbaOk10R8+SzZ6iEl8qLf0BrU
        MVnfNmoiKy0ZKdm9iIuZTA23lclmKoyWjPs1wXWHhL0/wg==
        -----END RSA PRIVATE KEY-----';

     private static $publicKey = '-----BEGIN PUBLIC KEY-----MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQC6fsWwsoYDwU1bnl65c1nWZyftQ8E50PvGQ0aAwchyLYKnEmRESZNS8/vy8VC7gwpvK1CxSM+91SxOhG13SaxkPUct58BjwjrGFttWaV2NGMfAG4YDBvVSG7RJ6VJCT7AA0OK7/n782H/KWBTeUuvhbs4x18lZM5kgozFE7U2KkQIDAQAB-----END PUBLIC KEY-----';

    private function __construct ()
    {
//        ECHO 'DS';
//        $this->rsa_private_key_path = __DIR__ . '/key/rsa_private_key.pem';
//        $this->rsa_public_key_path  = __DIR__ . '/key/rsa_public_key.pem';
//
//        file_exists($this->rsa_private_key_path) && file_exists($this->rsa_public_key_path) or die('私钥或公钥不存在');
//
//        extension_loaded('openssl') or die('缺少openssl扩展');
//
//        $this->privateKey = openssl_pkey_get_private(file_get_contents($this->rsa_private_key_path));
//        $this->publicKey = openssl_pkey_get_public(file_get_contents($this->rsa_public_key_path));
//
//        $this->privateKey && $this->publicKey or die('私钥或公钥不可用');

    }

    /**
     * 获取私钥
     * @return bool|resource
     */
    private static function getPrivateKey()
    {
        $privKey = self::$privateKey;
        return openssl_pkey_get_private($privKey);
    }

    /**
     * 获取公钥
     * @return bool|resource
     */
    private static function getPublicKey()
    {
        $publicKey = self::$publicKey;
        return openssl_pkey_get_public($publicKey);
    }



    /**
     * 单例
     * @return Rsa
     */
    public static function getInstance(){
        if(is_null(self::$_instance)){
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * 公钥加密
     * @param string $str
     */
    public function Base64Encrypt($str = ''){
        !empty($str) or die('要加密的原始数据为空');

        $str = json_encode($str);
        openssl_public_encrypt($str, $sign ,self::getPublicKey()) or die('加密数据出错');
        return  base64_encode($sign);

    }

    /**
     * 私钥解密
     * @param string $str
     */
    public function Base64Decrypt($str = ''){
        !empty($str) or die('要解密的数据为空');

        $str = base64_decode($str);
        openssl_private_decrypt($str,$design,self::getPrivateKey()) or die('解密数据出错');
        return json_decode($design);
    }

}

$rsa = Rsa::getInstance();

$sign = $rsa->Base64Encrypt('dsd');
echo $sign.PHP_EOL;
//$design = $rsa->Base64Decrypt($sign);
//echo $design;
