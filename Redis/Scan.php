<?php
/**
 * Scan 指令
 * Created by PhpStorm.
 * User: haibao
 * Date: 2019/2/21
 * Time: 3:45 PM
 */

require_once 'RedisBase.php';

/**
 * @var $db Redis
 */
$db = RedisBase::getInstance();

/*
 * -- key 指令
 *  这个指令使用非常简单，提供一个简单的正则字符串即可，但是有很明显的两个缺点。
 *  没有 offset、limit 参数，一次性吐出所有满足条件的 key，万一实例中有几百 w 个 key 满足条件，当你看到满屏的字符串刷的没有尽头时，你就知道难受了。
 *  keys 算法是遍历算法，复杂度是 O(n)，如果实例中有千万级以上的 key，这个指令就会导致 Redis 服务卡顿，
 *  所有读写 Redis 的其它的指令都会被延后甚至会超时报错，因为 Redis 是单线程程序，
 *  顺序执行所有指令，其它指令必须等到当前的 keys 指令执行完了才可以继续。
 *
 * -- scan 指令
 *  复杂度虽然也是 O(n)，但是它是通过游标分步进行的，不会阻塞线程;
 *  提供 limit 参数，可以控制每次返回结果的最大条数，limit 只是一个 hint，返回的结果可多可少;
 *  同 keys 一样，它也提供模式匹配功能;
 *  服务器不需要为游标保存状态，游标的唯一状态就是 scan 返回给客户端的游标整数;
 *  返回的结果可能会有重复，需要客户端去重复，这点非常重要;
 *  遍历的过程中如果有数据修改，改动后的数据能不能遍历到是不确定的;
 *  单次返回的结果是空的并不意味着遍历结束，而要看返回的游标值是否为零;
 */


$key = "key%d";

//插入数据
for ($i = 1; $i <= 10000; $i++){
    $db->set(sprintf($key,$i),$i,6);
}

/**
 * scan 参数提供了三个参数，第一个是 cursor 整数值，
 * 第二个是 key 的正则模式，第三个是遍历的 limit hint。
 * 第一次遍历时，cursor 值为 0，然后将返回结果中第一个整数值作为下一次遍历的 cursor。
 * 一直遍历到返回的 cursor 值为 0 时结束。
 */

//查找 以 key99 开头 key 列表。


$cursor = 0;
$keys = [];
do{
    $data =  $db->eval("return redis.call('scan','{$cursor}','match','key99*','count',1000)");
    $cursor = $data[0];
    foreach ($data[1] as $v){
        array_push($keys,$v);
    }
}while($cursor);

print_r($keys);