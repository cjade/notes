<?php
/**
 * GeoHash 附近的
 * Created by PhpStorm.
 * User: haibao
 * Date: 2019/2/21
 * Time: 3:08 PM
 */


require_once 'RedisBase.php';

/**
 * @var $db Redis
 */
$db = RedisBase::getInstance();


//增加 geoadd 指令携带集合名称以及多个经纬度名称三元组
echo $db->eval("return redis.call('geoadd','company',116.48105,39.996794,'juejin')");
echo PHP_EOL;
echo $db->eval("return redis.call('geoadd','company',116.514203,39.905409,'ireader')");
echo PHP_EOL;
echo $db->eval("return redis.call('geoadd','company',116.489033,40.007669,'meituan')");
echo PHP_EOL;
//这里可以加入多个三元组
echo $db->eval("return redis.call('geoadd','company',116.562108,39.787602,'jd',116.334255,40.027400,'xiaomi')");
echo PHP_EOL;

$db->expire('company',60);

// 距离 指令可以用来计算两个元素之间的距离，携带集合名称、2 个名称和距离单位。距离单位可以是 m、km、ml、ft，分别代表米、千米、英里和尺。
echo $db->eval("return redis.call('geodist','company','juejin','jd','km')");
echo PHP_EOL;

//geopos 指令可以获取集合中任意元素的经纬度坐标，可以一次获取多个。
//我们观察到获取的经纬度坐标和 geoadd 进去的坐标有轻微的误差，原因是 geohash 对二维坐标进行的一维映射是有损的，通过映射再还原回来的值会出现较小的差别。对于「附近的人」这种功能来说，这点误差根本不是事。
$xy1 =$db->eval("return redis.call('geopos','company','juejin')");
print_r($xy1);
echo PHP_EOL;
$xy2 =  $db->eval("return redis.call('geopos','company','xiaomi','jd')");
print_r($xy1);
echo PHP_EOL;

//附近的公司
//georadiusbymember 指令是最为关键的指令，它可以用来查询指定元素附近的其它元素，它的参数非常复杂。
//范围 20 公里以内最多 3 个元素按距离正排，它不会排除自身
$company =  $db->eval("return redis.call('georadiusbymember','company','xiaomi','20','km','count','3','asc')");
//范围 20 公里以内最多 3 个元素按距离倒排，它不会排除自身
$company =  $db->eval("return redis.call('georadiusbymember','company','xiaomi','20','km','count','3','desc')");

//三个可选参数 withcoord withdist withhash 用来携带附加参数
// withdist 很有用，它可以用来显示距离
$company =  $db->eval("return redis.call('georadiusbymember','company','xiaomi','20','km','withcoord','withdist','withhash','count','3','desc')");
print_r($company);


//除了 georadiusbymember 指令根据元素查询附近的元素，Redis 还提供了根据坐标值来查询附近的元素，
//这个指令更加有用，它可以根据用户的定位来计算「附近的车」，「附近的餐馆」等。
//它的参数和 georadiusbymember 基本一致，除了将目标元素改成经纬度坐标值。

$company =  $db->eval("return redis.call('georadius','company','116.514202','39.905409','20','km','withdist','count','3','desc')");

print_r($company);

//删除部分元素
$db->zRem('company','juejin','ireader','meituan');


