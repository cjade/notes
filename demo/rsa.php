<?php
/**
 * Created by PhpStorm.
 * User: haibao
 * Date: 2018/10/8
 * Time: 上午9:29
 */

class RsaException extends Exception{
    public function __construct($message = "", $code = 0, Throwable $previous = null) {
        parent::__construct($message, $code);
    }
}

class Rsa{
    static private $_instance;

    static private $_config = [
        'public_key' => '',
        'private_key' => '',
    ];

    static private $num = 0;

    private function __construct ()
    {
    }

    /**
     * 单例
     * @return Rsa
     */
    public static function getInstance(){
        if(!self::$_instance instanceof self){
            $rsa_private_key_path = __DIR__ . '/key/rsa_private_key.pem';
            $rsa_public_key_path  = __DIR__ . '/key/rsa_public_key.pem';

            file_exists($rsa_private_key_path) && file_exists($rsa_public_key_path) or self::RsaError('私钥或公钥不存在');

            extension_loaded('openssl') or self::RsaError('缺少openssl扩展');

            self::$_config['private_key'] = openssl_pkey_get_private(file_get_contents($rsa_private_key_path));
            self::$_config['public_key'] = openssl_pkey_get_public(file_get_contents($rsa_public_key_path));

            self::$_config['private_key'] && self::$_config['public_key'] or self::RsaError('私钥或公钥不可用');
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * 公钥加密
     * @param string $str
     */
    public function Base64Encrypt($str = ''){
        !empty($str) or self::RsaError('要加密的原始数据为空');

        $str = json_encode($str);
        openssl_public_encrypt($str, $sign ,self::$_config['public_key']) or self::RsaError('加密数据出错');
        return  base64_encode($sign);

    }

    /**
     * 私钥解密
     * @param string $str
     */
    public function Base64Decrypt($str = ''){
        !empty($str) or self::RsaError('要解密的数据为空');

        $str = base64_decode($str);
        openssl_private_decrypt($str,$design,self::$_config['private_key']) or self::RsaError('解密数据出错');
        return json_decode($design);
    }

    public function say(){
        echo 'say--'.self::$num.PHP_EOL;
    }

    public static function RsaError($message){
        throw new RsaException($message);
    }


}



try{
    $rsa = Rsa::getInstance();
    $sign = $rsa->Base64Encrypt('😀O(∩_∩)O哈哈~');
    echo $sign.PHP_EOL;
    $design = $rsa->Base64Decrypt($sign);
    echo $design;

}catch (RsaException $e){
    echo $e->getMessage();
}


