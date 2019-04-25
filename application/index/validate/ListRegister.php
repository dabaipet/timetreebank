<?php
namespace app\index\validate;

use think\Validate;

class ListRegister extends Validate
{
    protected $rule = [
        'regMobile'  =>  'require|number|max:11',
        'code' =>'number|length:6',
        'regPassword' =>  'number',
    ];

    protected $message  =   [
        'regMobile.require' => '请填写手机号1',
        'regMobile.max'     => '请输入有效的手机号码2',
        'regMobile.number'  =>'请输入有效的手机号码1',
        'code.number'       =>'短信验证码必须是数字',
        'code.length'       =>'短信验证码是六位数字',
        'regPassword.number' => '年龄必须是数字',
        'age.between'  => '年龄只能在1-120之间',
        'email'        => '邮箱格式错误',
    ];
}