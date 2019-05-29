<?php
/*
 * 时间树
 * 首页控制器
 * 2019.5.29
 * */
namespace app\index\controller;

use think\Controller;

class Index extends Base
{
    public function index()
    {

        echo dirname(dirname(dirname(__FILE__))).'\webset'.'.php';
        return $this->fetch("index");
    }


}