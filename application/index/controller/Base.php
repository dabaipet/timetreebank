<?php
/**
 * 基类控制器
 *
 */

namespace app\index\controller;

use think\Controller;
use think\Request;
use think\Session;
use think\Log;

class Base extends Controller
{
    protected  $regIp = ""; //ip

    public  function __construct(Request $request = null)
    {
        parent::__construct();
        self::checkIpAddress();

    }
    /*
     * 地址是否为空
     * */
    function checkIpAddress(){

    }
}