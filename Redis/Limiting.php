<?php
/**
 * 限流
 * Created by PhpStorm.
 * User: haibao
 * Date: 2019/2/21
 * Time: 11:16 AM
 */

require_once 'RedisBase.php';

class Limiting{

    /**
     * 限定用户的某个行为在指定的时间里只能允许发生 N 次
     * @param $user_id      用户
     * @param $action_key   行为
     * @param $period       时间秒
     * @param $max_count    次数
     */
    public function isActionAllowed($user_id, $action_key, $period, $max_count){
        $key = sprintf('hist:%s:%s', $user_id, $action_key);
        $current_time = microtime(true);//毫秒

        $db = RedisBase::getInstance();
        //记录行为
        $db->zAdd($key, $current_time, $current_time);

        //移除时间窗口之前的行为记录，剩下的都是时间窗口内的
        $db->zRemRangeByScore($key, 0, $current_time - $period);

        //获取窗口内的行为数量
        $current_count = $db->zCard($key);

        //设置 zset 过期时间，避免冷用户持续占用内存
        //过期时间应该等于时间窗口的长度，再多宽限 1s
        $db->expire($key, $period +1);

        //比较数量是否超标
        return $current_count <= $max_count;

    }


    /**
     * 限流测试
     */
    public function test(){
        for ($i = 0; $i < 20; $i++){
            $bool =  $this->isActionAllowed("Jade", "reply", 60, 5);
            echo $bool;
            echo PHP_EOL;
        }
    }
}

$test = new Limiting();

$test->test();