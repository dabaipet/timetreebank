<?php


namespace app\index\controller;

use think\Controller;
use app\index\model\Smsm;
use app\index\model\Member;
use think\Request;
use think\Session;
use think\Url;

/*
 * ==用户找回密码==
 * */
class Findpassword extends Controller
{

    public  $controller;

    public  function __construct(Request $request = null)
    {
        parent::__construct();
        $request = Request::instance();
        $this->controller = $request->controller();
    }
    //首页
    public function index(){
        $controller = $this->controller;
        return $this->fetch("index",['controller'=>$controller]);
    }
    //找回密码处理
    public function passwordDo(){
        $Mobile =  input('regMobile'); // 手机号
        $captcha = input('imgVerifycode');//图形
        $regmCode = input('regmCode');// 验证码
        //验证图形验证码
        if(!captcha_check($captcha)){
            return ['status'=>0,'msg'=>"请输入正确的图形验证码!"];
            die();
        }
        //验证数据
        $rule = [
            'regMobile'  =>  $Mobile,
            'code' =>  $regmCode
        ];
        //加载验证器
        $msgValidate = $this->validate($rule,'Register');
        if(true !== $msgValidate){
            return ['status'=>0,'msg'=>"$msgValidate"];
        }
        $member = new Member();
        $memberR = $member->getMemberone("$Mobile");
        if (empty($memberR)){
            return ['status'=>0,'msg'=>"手机号不存在!"];
            die();
        }
        if(Session::get("$Mobile") == $regmCode){
            Session::set('isPhoneSet',$Mobile);
            $Turl = Url('/index/findpassword/set');
            return ['status'=>1,'Turl'=>"$Turl"];
        }
    }
    //找回密码设置
    public function Set(){
       if (!Session::has('isPhoneSet')){
           $this->success("非法请求!",Url('/index/findPassword/'));
       }
        $isPhoneSet = Session::get('isPhoneSet');
        return $this->fetch("set",['isPhoneSet'=>$isPhoneSet]);
    }
    //找回密码设置
    public function SetDo(){
        $Mobile =  input('regMobile'); // 手机号
        $restPassword = input('restPassword');//二次密码
        $regPassword = input('regPassword');// 一次密码

        if(Session::get('isPhoneSet') != $Mobile){
            return ['status'=>0,'msg'=>"提交的手机号不一致!"];
            die();
        }
        if ($restPassword != $regPassword){
            return ['status'=>0,'msg'=>"两次密码不一致!"];
            die();
        }
        //验证数据
        $rule = [
            'regMobile'  =>  $Mobile,
            'regPassword' =>  $regPassword,
        ];
        //加载验证器
        $msgValidate = $this->validate($rule,'Register');
        if(true !== $msgValidate){
            return ['status'=>0,'msg'=>"$msgValidate"];
            die();
        }
        //加密插件 phpass-3.0
        $PasswordHashs = new \PasswordHashs(8, false);
        $hashedPassword = $PasswordHashs->HashPassword("$regPassword"); // 计算密码的哈希
        //更新密码
        $updateStats = Member::where('phone', $Mobile)
            ->update(['password' => $hashedPassword]);
       /* $Member = new Member();
        $updateStats = $Member->updateMemberone("$Mobile","$hashedPassword");*/
        if ($updateStats == ''){
            return ['status'=>0,'msg'=>"密码设置失败,请重新设置!"];
        }else{
            $Turl = Url('/index');
            return ['status'=>1,'msg'=>"密码设置成功!",'Turl'=>"$Turl"];
        }

    }


}