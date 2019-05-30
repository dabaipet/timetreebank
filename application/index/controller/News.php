<?php
/*
 * 文章控制器
 * */

namespace app\index\controller;

use think\Request;

class News extends Base
{


    public function index(Request $request = null)
    {

        return $this->fetch("index");
    }

    public function details()
    {

    }

}