<?php
namespace app\index\controller;

use think\Request;

/*
 * ==用户登录控制器==
 * @user    用户登录页面
 * @userHandle  用户登录处理
 * @userMobile  用户手机登录
 * @userMobileHandle    用户手机登录处理
 * @doctor  医生登录
 * @doctor  医生登录处理
 * @loginSms    手机发送短信 登录所有适用 *
 * */

class Signin extends Base
{
    public function  index()
    {

        return $this->fetch("user");
    }
    public function  user()
    {
        //$this->RedisSession->read("15210086671");
        return $this->fetch("user");
    }
    public  function userHandle(Request $request=null){
        if($request->isAjax()) {
            $Mobile = input('signinMobile'); // 手机号
            $captcha = input('imgVerifycode');//图形
            $Password = input('signinPassword');// 密码
            $regmCode = input('regmCode');// 手机验证码
            //验证数据
            $rule = ['regMobile' => $Mobile, 'regPassword' => $Password];
            //加载验证器
            $msgValidate = $this->validate($rule, 'Register');
            if (true !== $msgValidate) {
                return ['status' => 0, 'msg' => "$msgValidate"];
            }
            //验证图形验证码
            if (!captcha_check($captcha)) {
                $codeMsg = $this->showReturnCodeMsg('4000');
                return ['status' => 0, 'msg' => "$codeMsg[msg]"];
            };
            //手机验证码登录
            if($this->RedisSession->read("$Mobile") != $regmCode){
                echo "11";

            }
            $member = new Member();
            $memberOne = $member->getMemberone("dabai_member", "$Mobile");
            if (empty($memberOne)) {
                $codeMsg = $this->showReturnCodeMsg('4001');
                return ['status' => 0, 'msg' => "$codeMsg[msg]"];
            }
            //检测密码
            $PassworCheck = $member->PassWordCheck("dabai_member", $memberOne['phone'], "$Password");
            if ($PassworCheck) {
                $this->RedisSession->write($memberOne['phone'], 'DABAI' . $Mobile);
                $member->MemberSetInc("dabai_member", "$Mobile");
                $codeMsg = $this->showReturnCodeMsg('1003');
                $Turl = Url('/');
                return ['status' => 1, 'msg' => "$codeMsg[msg]", 'Turl' => "$Turl"];
            } else {
                $codeMsg = $this->showReturnCodeMsg('1005');
                return ['status' => 0, 'msg' => "$codeMsg[msg]"];
            }
        }

    }
    //用户手机登录
    public function  userMobile()
    {
        $controller = $this->controller;

        return $this->fetch("usermobile",['controller'=>$controller]);
    }

    //医生登录
    public function  doctor()
    {
        echo 1112222;

    }
    //找回密码
    public function  findPassword()
    {
        return $this->fetch("findpassword");
    }

    //登录手机验证码
    /*public function loginSms(){
        $phoneNumbers =  input('regMobile');
        $templateCode = "SMS_109420230";
        $regSms = new Smsm();
        $codeState = $regSms ->smsmSend("$phoneNumbers","$templateCode");

        if ($codeState == true) {
            return ['status'=>1,'msg'=>"发送成功"];
        }else{
            return ['status'=>0,'msg'=>"发送失败"];
        }
    }*/

}
