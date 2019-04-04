<?php
/**
 * Created by PhpStorm.
 * User: haibao
 * Date: 2019/3/7
 * Time: 3:30 PM
 */


class UserService
{
    public static function getUserInfo(int $uid): array
    {
        return [
            'id' => $uid,
            'user_name' => 'jade_' . $uid,
        ];
    }
}


$serviceName = trim($_GET['service_name']);
$serviceAction = trim($_GET['action_name']);
$argv = file_get_contents("php://input");

if(empty($serviceName) || empty($serviceAction) ) exit('parameter is missing');

if(!empty($argv)) $argv = json_decode($argv,true);

$result = call_user_func_array([$serviceName,$serviceAction], $argv);

echo json_encode($result);

