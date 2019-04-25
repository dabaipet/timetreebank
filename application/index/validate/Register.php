<?php
namespace app\index\validate;

use think\Validate;

class Register extends Validate
{
    protected $rule = [
        'mobile'  =>  'require|number|max:11',
        'code' =>'require|number|length:6',
        'password' =>  'require|alphaNum',
        'name' =>  'require|max:10',//医生注册补充

        'title' =>  'require',
        'd_Number' =>  'require',
    ];

    protected $message  =   [
        'mobile.require' => '手机号不能为空',
        'mobile.max'     => '请输入有效的手机号码',
        'mobile.number'  =>'请输入有效的手机号码',

        'code.require' => '短信验证码不能为空',
        'code.number'       =>'短信验证码必须是数字',
        'code.length'       =>'短信验证码是六位数字',

        'password.require' => '密码不能为空',
        'password.alphaNum' => '密码由数字加字母',


        'title.require' => '医院名称不能为空',
        'd_Number.require' => '证书编号不能为空',

        'email'        => '邮箱格式错误',

        'name.require' => '请填写真实姓名',
        'name.max'     => '姓名不规范',
    ];


}