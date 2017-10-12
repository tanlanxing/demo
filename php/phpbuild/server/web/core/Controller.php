<?php
/**
 * Created by PhpStorm.
 * User: mulangcloud
 * Date: 2017/10/12
 * Time: 15:34
 */

namespace server\web\core;


use Swoole\Http\Request;
use Swoole\Http\Response;

class Controller
{
    protected $request;
    protected $response;
    protected $actionId;

    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    public function setActionId(string $actionId)
    {
        $this->actionId = str_replace('Action', '', $actionId);
    }

    public function redirect(string $uri)
    {
        $this->response->header('location', $uri);
        return $this->response->end();
    }

    public function render(string $template, array $data = null):string
    {
        $class = get_class($this);
        $path = APP_ROOT . '/' . dirname(dirname(str_replace('\\', '/', $class))) . '/views/';
        $file = $path . $template . '.php';
        if (!file_exists($file)) {
            echo "file: $file no exists!";
            return '';
        }
        if (is_array($data)) {
            extract($data);
        }
        ob_start();
        include $file;
        return ob_get_clean();
    }

    public function autoRender(array $data = null):string
    {
        $class = get_class($this);
        $path = explode('\\', $class);
        $controller = strtolower(str_replace('Controller', '', end($path)));
        $template = $controller . '/' . $this->actionId;
        return $this->render($template, $data);
    }

    public function renderJson(array $data)
    {
        $this->response->header('Content-type', 'application/json');
        return json_encode($data);
    }
}