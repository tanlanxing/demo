<?php

require __DIR__ . '/autoload.php';

$server = new server\web\Application();
$server->run();