<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/15
 * Time: 17:18
 */

namespace app\index\controller;

use think\Controller;
use think\Request;
use think\Session;

class Check extends Controller
{

    public function isCheckUser(){
        if (Session::has('isPhone')){
            $this->success("已登录",Url('/index'));
        }
    }
    public function isCheckDoctor(){
        if (!Session::has('isPhone')){
            $this->error("跳转登录页面",Url('/index/Login/doctor'));
        }
    }

}