<?php
/**
 * Created by PhpStorm.
 * User: haibao
 * Date: 2019/2/21
 * Time: 11:17 AM
 */


class RedisBase{

    static private $_instance;


    private function __construct ()
    {
        self::$_instance = new Redis();
        self::$_instance->connect('127.0.0.1',6379);
    }

    /**
     * 单例
     * @return mixed
     */
    public static function getInstance()
    {
        if(!self::$_instance instanceof self){
            new self();
        }
        return self::$_instance;
    }
}