<?php
/**
 * 漏斗限流
 * Created by PhpStorm.
 * User: haibao
 * Date: 2019/2/21
 * Time: 11:48 AM
 */

require_once 'RedisBase.php';

class Funnel{

    /**
     * 漏斗容量
     * @var int
     */
    protected $capacity;

    /**
     * 漏嘴流水速度
     * @var float
     */
    protected $leakingRate;

    /**
     * 漏斗剩余空间
     * @var int
     */
    protected $leftQuota;

    /**
     * 上一次漏水时间
     * @var int
     */
    protected $leakingTs;

    /**
     * 初始化
     * Funnel constructor.
     * @param $capacity     漏斗容量
     * @param $leakingRate  漏嘴流水速度
     */
    public function __construct($capacity, $leakingRate)
    {
        $this->capacity = $capacity;
        $this->leakingRate = $leakingRate;
        $this->leftQuota = $capacity;
        $this->leakingTs = time();
    }


    public function makeSpace(){
        $nowTs = time();

        //距离上一次漏水过去了多久
        $deltaTs = $nowTs - $this->leakingTs;
        //又可以腾出不少空间了
        $delaQuota = (int)($deltaTs * $this->leakingRate);
        // 间隔时间太长，整数数字过大溢出
        if($delaQuota < 0 ){
            $this->leftQuota = $this->capacity;
            $this->leakingTs = $nowTs;
            return;
        }

        // 腾出空间太小，最小单位是1
        if($delaQuota < 1){
            return;
        }

        //增加剩余空间
        $this->leftQuota += $delaQuota;
        //记录漏水时间
        $this->leakingTs = $nowTs;

        //剩余空间不得高于容量
        if($this->leftQuota > $this->capacity){
            $this->leftQuota = $this->capacity;
        }
    }

    /**
     * 漏水
     * @param int $quota
     * @return bool
     */
    public function watering($quota = 1){
        $this->makeSpace();
        if($this->leftQuota >= $quota){
            $this->leftQuota -= $quota;
            return true;
        }

        return false;
    }

    /**
     * @param $user_id
     * @param $action_key
     * @param $capacity     漏斗容量
     * @param $leaking_rate 漏嘴流水速率 quota/s
     */
    public function isActionAllowd($user_id, $action_key, $capacity, $leaking_rate){
        $key = sprintf('hist:%s:%s', $user_id, $action_key);

    }
}