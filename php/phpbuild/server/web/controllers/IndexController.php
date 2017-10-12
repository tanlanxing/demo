<?php
/**
 * Created by PhpStorm.
 * User: mulangcloud
 * Date: 2017/10/12
 * Time: 13:20
 */

namespace server\web\controllers;


use server\web\core\Controller;

class IndexController extends Controller
{
    public function indexAction()
    {
        $data = $this->request->post;
        $data['rand'] = rand(1000, 9999);
        return $this->autoRender($data);
    }
}