<?php 
use Workerman\Worker;

define("PROJECT_ROOT", __DIR__);

//platform适应器
if (PHP_OS == 'WINNT') {
    require PROJECT_ROOT . '/workerman-for-win-master/Autoloader.php';
} else {
    require PROJECT_ROOT . '/Workerman-master/Autoloader.php';
}

$websocketWorker = new Worker('websocket://0.0.0.0:9501');
// 启动1个进程对外提供服务，使用非共享内存
$http_worker->count = 1;

//on-connect
$websocketWorker->onConnect = function ($connection)  {
    
};

//on-message
$worker->onMessage = function($connection, $data)
{
    var_dump($data);
    $connection->send('receive success');
};

//on-close
$worker->onClose = function($connection)
{
    echo "connection closed\n";
};

//on-error
$worker->onError = function($connection, $code, $msg)
{
    echo "error $code $msg\n";
};

Worker::runAll();