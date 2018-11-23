<?php
/**
 * Created by PhpStorm.
 * User: haibao
 * Date: 2018/11/19
 * Time: 10:54 AM
 */

//协程

class A{
    protected $fileHandle;

    public function __construct ($file)
    {
        $this->fileHandle = fopen($file,'a');

    }
    public function __destruct ()
    {
        fclose($this->fileHandle);
    }

    function logger(){
        while (true){
            $a = yield;
            var_dump('a:'.$a);
            $b = yield;
            var_dump('b:'.$b);
            fwrite($this->fileHandle, $a."\n".$b."\n");
        }
    }

}
echo round(memory_get_usage() / 1024 / 1024, 2) . ' MB' . PHP_EOL;

$file = __DIR__.'/data/test.log';
$aa = new A($file);


$logger = $aa->logger();
$logger->send('Foo');
$logger->send('Foo1');
$logger->send('Foo2');
$logger->send('Foo3');
