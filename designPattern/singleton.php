<?php
/**
 * Created by PhpStorm.
 * User: haibao
 * Date: 2018/9/11
 * Time: 下午4:45
 */

class Singleton{
    static private $_instance;
    static private $_redis;


    private function __construct ()
    {
    }

    static public function getInstance()
    {
        if(!self::$_instance instanceof self){
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function redis(){
        if(!self::$_redis instanceof self){
            $redis = new Redis();
            $redis->connect('127.0.0.1');
            $redis->select(0);
            self::$_redis = $redis;
        }

        return self::$_redis;
    }

    public function aa(){

    }

}

//$redis = Singleton::getInstance()->redis();
////$expire = strtotime(date('Y-m-d') . " 23:59:59") - time();
//$redis->set('aa','bb',$expire);
