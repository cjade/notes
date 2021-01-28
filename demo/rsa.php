<?php
/**
 * 数据加密
 * Created by PhpStorm.
 * User: haibao
 * Date: 2018/10/8
 * Time: 上午9:29
 */
trait Rsa
{

    /**
     * 数据有效期(分钟)
     * @var int
     */
    protected $life_time = 1;

    /**
     * 公钥
     * @var
     */
    private $pubKay;
    /**
     * 私钥
     * @var
     */
    private $priKay;


    /**
     * 生成密钥
     * @return array
     * @throws ApiException
     */
    public function generateSecret(): array
    {
        $config = [
            "digest_alg"       => "sha512",
            "private_key_bits" => 1024,           //字节数  512 1024 2048  4096 等 ,不能加引号，此处长度与加密的字符串长度有关系
            "private_key_type" => OPENSSL_KEYTYPE_RSA,   //加密类型
        ];
        extension_loaded('openssl') or self::RsaError('缺少openssl扩展');
        //生成证书
        $res = openssl_pkey_new($config);
        //提取私钥
        openssl_pkey_export($res, $private_key);
        //生成公钥
        $details = openssl_pkey_get_details($res);
        //提取公钥
        $public_key = $details['key'];
        // 释放密钥资源
        openssl_pkey_free($res);
        return compact('private_key', 'public_key');
    }

    /**
     * 设置私钥
     * @param mixed $priKay
     */
    private function setPriKay($priKay = ''): void
    {
        extension_loaded('openssl') or self::RsaError('缺少openssl扩展');
        if(empty($priKay)) {
            $rsa_private_key_path  = __DIR__ . '/key/rsa_private_key.pem';
            file_exists($rsa_private_key_path) or self::RsaError('私钥不存在');
            $priKay = file_get_contents($rsa_private_key_path);
        }
        $this->priKay = openssl_pkey_get_private($priKay);
    }

    /**
     * 设置公钥
     * @param mixed $pubKay
     */
    private function setPubKay($pubKay = ''): void
    {
        extension_loaded('openssl') or self::RsaError('缺少openssl扩展');
        if(empty($pubKay)) {
            $rsa_public_key_path  = __DIR__ . '/key/rsa_public_key.pem';
            file_exists($rsa_public_key_path) or self::RsaError('公钥不存在');
            $pubKay = file_get_contents($rsa_public_key_path);
        }
        $this->pubKay = openssl_pkey_get_public($pubKay);
    }

    /**
     * 私钥加密
     * @param        $originalData
     * @param string $priKay 私钥
     * @return mixed
     * @throws ApiException
     */
    public function rsaPrivateEncrypt($originalData, $priKay = '')
    {
        !empty($originalData) or self::RsaError('要加密的数据为空');
        $this->setPriKay($priKay);
        $originalData = json_encode($originalData, JSON_UNESCAPED_UNICODE);
        $crypto       = '';
        foreach (str_split($originalData, 117) as $chunk) {
            openssl_private_encrypt($chunk, $encryptData, $this->priKay) or self::RsaError('加密失败');
            $crypto .= $encryptData;
        }
        openssl_pkey_free($this->priKay);
        return base64_encode($crypto);
    }

    /**
     * 公钥加密
     * @param        $originalData
     * @param string $pubKey
     * @return mixed
     * @throws ApiException
     */
    public function rsaPublicEncrypt($originalData, $pubKey = '')
    {
        !empty($originalData) or self::RsaError('要加密的数据为空');
        $this->setPubKay($pubKey);
        $originalData = json_encode($originalData, JSON_UNESCAPED_UNICODE);
        $crypto       = '';
        foreach (str_split($originalData, 117) as $chunk) {
            openssl_public_encrypt($chunk, $encryptData, $this->pubKay) or self::RsaError('加密失败');
            $crypto .= $encryptData;
        }
        openssl_pkey_free($this->pubKay);
        return base64_encode($crypto);
    }

    /**
     * 公钥解密
     * @param string $encryptData
     * @param string $pubKey
     * @return mixed
     * @throws ApiException
     */
    public function rsaPublicDecrypt(string $encryptData, $pubKey = '')
    {
        !empty($encryptData) or self::RsaError('要解密的数据为空');
        $this->setPubKay($pubKey);
        $encryptData = base64_decode($encryptData);
        $crypto      = '';
        foreach (str_split($encryptData, 128) as $chunk) {
            openssl_public_decrypt($chunk, $decryptData, $this->pubKay) or self::RsaError('数据校验失败');
            $crypto .= $decryptData;
        }
        openssl_pkey_free($this->pubKay);
        $data     = json_decode($crypto, true);
        $exp_time = $data['_timestamp'] + ($this->life_time * 60);
        $exp_time > time() or self::RsaError('数据已失效');
        return $data;
    }

    /**
     * 私钥解密
     * @param string $encryptData
     * @param string $priKay
     * @return mixed
     * @throws ApiException
     */
    public function rsaPrivateDecrypt(string $encryptData, $priKay = '')
    {
        !empty($encryptData) or self::RsaError('要解密的数据为空');
        $this->setPriKay($priKay);
        $encryptData = base64_decode($encryptData);
        $crypto      = '';
        foreach (str_split($encryptData, 128) as $chunk) {
            openssl_private_decrypt($chunk, $decryptData, $this->priKay) or self::RsaError('数据校验失败!');
            $crypto .= $decryptData;
        }
        openssl_pkey_free($this->priKay);
        $data     = json_decode($crypto, true);
        $exp_time = $data['_timestamp'] + ($this->life_time * 60);
        $exp_time > time() or self::RsaError('数据已失效');
        return $data;
    }


    /**
     * 抛出异常
     * @param string $message
     * @param int    $code
     * @throws ApiException
     */
    public static function RsaError(string $message, int $code = 4011)
    {
        throw new ApiException($message, $code);
    }
}





