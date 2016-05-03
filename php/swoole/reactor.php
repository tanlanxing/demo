<?php
$serv = new swoole_server("127.0.0.1", 9501, SWOOLE_BASE, SWOOLE_SOCK_TCP);

$serv->set([
	'reactor_num' => 1,
	'worker_num' => 1,
	'backlog' => 128,
	'max_request' => 50,
	'dispatch_mode' => 1,
	'max_conn' => 100,
	'daemonize' => 1,
	'log_file' => __DIR__.'/swoole.log'
]);

$serv->on('connect', function ($serv, $fd){
    echo "Client:Connect.\n";
});

$serv->on('receive', function ($serv, $fd, $from_id, $data) {
    echo "Receive data: $data\n";
    /*$serv->send($fd, 'Swoole: '.$data);
    $serv->close($fd);*/
    exec($data, $output, $ret);
    $output[] = $ret;
    echo json_encode($output);
    $serv->send($fd, json_encode($output)); 
});

$serv->on('close', function ($serv, $fd) {
    echo "Client: Close.\n";
});

$serv->start();
