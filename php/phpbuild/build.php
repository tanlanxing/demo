<?php
if (class_exists('Phar')) {
    $phar = new Phar('swoole.phar', 0, 'swoole.phar');
    $phar->buildFromDirectory(__DIR__ . '/');
    $phar->setStub(file_get_contents('index.php'));
    $phar->compressFiles(Phar::GZ);
} else {
    exit('no Phar modules');
}

