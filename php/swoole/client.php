<?php
$client = new swoole_client(SWOOLE_SOCK_TCP);
if (!$client->connect('127.0.0.1', 9501, 2))
{
    exit("connect failed. Error: {$client->errCode}\n");
}
$client->send("la -al\n");
$response = $client->recv();
print_r(json_decode($response));
$client->close();

/*
$client = new swoole_client(SWOOLE_SOCK_TCP, SWOOLE_SOCK_ASYNC);
$client->on("connect", function(swoole_client $cli) {
    echo "begin send data\n";
    $cli->send("ls");
});
$client->on("receive", function(swoole_client $cli, $data){
    echo "Receive: $data";
    $cli->close();
    //$cli->send(str_repeat('A', 100)."\n");
    //sleep(1);
});
$client->on("error", function(swoole_client $cli){
    echo "error\n";
});
$client->on("close", function(swoole_client $cli){
    echo "Connection close\n";
});
$client->connect('127.0.0.1', 9501);
*/
