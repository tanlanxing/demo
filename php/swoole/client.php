<?php
/*$client = new swoole_client(SWOOLE_SOCK_TCP);
if (!$client->connect('127.0.0.1', 9501, 2))
{
    exit("connect failed. Error: {$client->errCode}\n");
}
$client->send("ls -al\n");
$response = $client->recv();
print_r(json_decode($response));
$client->close();*/


$client = new swoole_client(SWOOLE_SOCK_TCP, SWOOLE_SOCK_ASYNC);
$client->on("connect", function(swoole_client $cli) {
    echo "begin send data\n";
    $cli->send("ls -al");
});
$client->on("receive", function(swoole_client $cli, $data){
    echo "Receive: ";
    print_r(json_decode($data, true));
    $cli->close();
    //$cli->send(str_repeat('A', 100)."\n");
    //sleep(1);
});
$client->on("error", function(swoole_client $cli){
    echo $cli->errCode, ':', socket_strerror($cli->errCode), PHP_EOL;
});
$client->on("close", function(swoole_client $cli){
    echo "Connection close\n";
});
$client->connect('127.0.0.1', 9501);

