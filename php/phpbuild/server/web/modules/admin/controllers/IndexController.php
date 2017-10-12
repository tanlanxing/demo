<?php
/**
 * Created by PhpStorm.
 * User: mulangcloud
 * Date: 2017/10/12
 * Time: 17:03
 */

namespace server\web\modules\admin\controllers;


use server\web\core\Controller;

class IndexController extends Controller
{
    public function indexAction()
    {
        return $this->actionId;
    }
}