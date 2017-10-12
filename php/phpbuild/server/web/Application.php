<?php
/**
 * Created by PhpStorm.
 * User: mulangcloud
 * Date: 2017/10/12
 * Time: 11:24
 */

namespace server\web;

use server\web\core\Router;
use Swoole\Http\Server;
use Swoole\Http\Response;
use Swoole\Http\Request;

class Application
{
    /**
     * @var Server
     */
    private $httpServer;

    public function __construct()
    {
        $this->httpServer = new Server("0.0.0.0", 9501);
        $this->init();
    }

    public function init()
    {
        $this->httpServer->set(array(
            'upload_tmp_dir' => APP_ROOT . '/uploadfiles/',
            'http_parse_post' => true,
            'document_root' => APP_ROOT . '/assets/',
            'enable_static_handler' => true,
        ));
        $this->httpServer->on('request', function (Request $request, Response $response) {
            $router = new Router($request, $response);
            $router->dispatch();
            unset($router);
        });
    }

    public function run()
    {
        $this->httpServer->start();
    }

}