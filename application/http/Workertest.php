<?php
namespace app\http;

use think\facade\Env;
use think\worker\Server;

class Workertest extends Server{


    protected $host = '127.0.0.1';
    protected $port = 2801;
    protected $option = [
        'count'=> 4,
        'pidFile'=> Env::get('runtime_path') . 'worker2.pid',
        'name'		=> 'think'
        ];

    public function onMessage($connection, $data)
    {
        $connection->send('receive success');
    }
}
