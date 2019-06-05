<?php
namespace app\index\controller;
/*
 * @用户和机构注册
 * user
 * org
 * */

use think\Request;

class Signup extends Base
{
    /*用户注册*/
    public function  user()
    {
        return $this->fetch('user');
    }
    /*普通用户注册处理*/
    public function reguser(Request $request=null)
    {
        if($request->isAjax()){
            $Mobile =  input('regMobile'); // 手机号
            $code = input('regmCode');//手机验证码
            $Password = input('regPassword');// 密码
            //验证数据
            $rule = [ 'mobile'=>$Mobile,'code'=>$code,'password'=>$Password];
            //加载验证器
            $msgValidate = $this->validate($rule,'Register');
            if(true !== $msgValidate){
                return ['status'=>0,'msg'=>"$msgValidate"];
            }
            //验证手机验证码
            if($this->RedisSession->read("$Mobile") != $code){
                $codeMsg = $this->showReturnCodeMsg('3003');
                return ['status'=>0,'msg'=>"$codeMsg[msg]"];
            };
            $member = new Member();
            if (!empty($member->getMemberone("dabai_member","$Mobile"))){
                $codeMsg = $this->showReturnCodeMsg('4005');
                return ['status'=>0,'msg'=>"$codeMsg[msg]"];
            }
            $memberStats = $member->addMember("$Mobile","$Password");
            if ($memberStats == true){
                $codeMsg = $this->showReturnCodeMsg('1006');
                $Turl = Url('/');
                return ['status'=>1,'msg'=>"$codeMsg[msg]",'Turl'=>"$Turl"];
            }else{
                $codeMsg = $this->showReturnCodeMsg('1007');
                return ['status'=>0,'msg'=>"$codeMsg[msg]"];
            }
        }
    }
    /*机构注册*/
    public function org(Request $request=null)
    {
        if ($request->isAjax()){
            $name = input('d_Name');
            $sex = input('d_Sex');
            $title = input('d_Title');
            $d_Number = input('d_Number');
            $d_Img = input('d_Img');
            $mobile = input('Mobile');
            $p_Code = input('p_Code');
            $m_Code = input('m_Code');
            $d_Password = input('d_Password');
            //验证数据
            $rule = ['name' => $name,'title' => $title, 'd_Number' => $d_Number,'mobile' => $mobile,'code' => $m_Code,'password' => $d_Password];
            //加载验证器
            $msgValidate = $this->validate($rule,'Register');
            if(true !== $msgValidate){
                return ['code'=>202,'msg'=>"$msgValidate"];
            }
            //验证图形验证码
            if(!captcha_check($p_Code)){
                $codeMsg = $this->showReturnCodeMsg('4000');
                return ['code'=>202,'msg'=>"$codeMsg[msg]"];
            };
            //验证手机验证码
            if($this->RedisSession->read("$mobile") != $m_Code){
                $codeMsg = $this->showReturnCodeMsg('3003');
                return ['code'=>202,'msg'=>"$codeMsg[msg]"];
            };
            $doctor = new DoctorModel();
            //医生手机号是否已注册
            if (!empty($doctor->getDoctorOne("$mobile"))){
                $codeMsg = $this->showReturnCodeMsg('4005');
                return ['code'=>202,'msg'=>"$codeMsg[msg]"];
            }
            //证书编号查询
            if (!empty($doctor->getCertificatenu("$d_Number"))){
                $codeMsg = $this->showReturnCodeMsg('4006');
                return ['code'=>202,'msg'=>"$codeMsg[msg]"];
            }
            //数据插入
            $regData = ['name' => $name, 'sex' => $sex, 'phone' => $mobile,'password' => $d_Password,'Cer_nunber'=>$d_Number,'Cer_img'=>$d_Img];
            $doctorStats = $doctor->addDoctorOne("$regData");
            if ($doctorStats == true){
                $codeMsg = $this->showReturnCodeMsg('1006');
                $Turl = Url('/');
                return ['code'=>200,'msg'=>"$codeMsg[msg]",'Turl'=>"$Turl"];
            }else{
                $codeMsg = $this->showReturnCodeMsg('1007');
                return ['code'=>202,'msg'=>"$codeMsg[msg]"];
            }
        }
        return $this->fetch('org');
    }

}
