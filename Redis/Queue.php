<?php
/**
 * Created by PhpStorm.
 * User: haibao
 * Date: 2019/2/21
 * Time: 11:36 AM
 */

require_once 'RedisBase.php';

class Queue{

    /**
     * 队列
     */
    public function queues(){
        //锁 由于 setnx 和 expire 是两条指令而不是原子指令
        //如果在 setnx 和 expire 之间服务器进程突然挂掉了，可能是因为机器掉电或者是被人为杀掉的，就会导致 expire 得不到执行，也会造成死锁
        //下面方法可以使这两个指令一起执行避免死锁
        //$res = $this->rDb->set('key',1,['nx','ex'=>10]);

        $db = RedisBase::getInstance();

        $key = 'list';

        //入队
        for ($i = 1; $i <= 20; $i++){
            $db->rPush($key,$i);
        }


        //出队 待优化
        while (true){
            try{
                //阻塞读
                $val = $db->blPop($key,10);
            }catch (RedisException $r){
                echo print_r($r,true);
            }

            echo date('Y-m-d H:i:s').print_r($val,true);
        }
    }
}


$queue = new Queue();

$queue->queues();