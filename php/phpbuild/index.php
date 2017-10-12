#!/usr/bin/env php
<?php
Phar::mapPhar('swoole.phar');
if ($argc < 2) {
    $platform = 'server';
} else {
    $platform = $argv['1'];
}
require 'phar://swoole.phar/' . $platform . '.php';
__halt_compiler();

