<?php
/**
 * Created by PhpStorm.
 * User: mulangcloud
 * Date: 2017/10/12
 * Time: 13:20
 */

namespace server\web\controllers;


use server\web\core\Controller;

class AdminController extends Controller
{
    public function indexAction()
    {
        $this->autoRender();
        return '<h1>Hello Swoole. #'.rand(1000, 9999).'</h1><script type="application/javascript" src="/js/vue.min.js"></script>';
    }

    public function dataAction()
    {
        return $this->renderJson(['a' => 'b']);
    }
}