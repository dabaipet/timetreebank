<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/17
 * Time: 14:26
 */

namespace app\index\controller;

use app\index\model\Member;
use think\Controller;
use think\Request;
//use think\Session;
use think\cache\driver\Redis;
use think\session\driver\Redis as RedisSession;
use think\Log;

class Base extends Controller
{
    protected  $regIp = ""; //ip
    protected  $controller;
    protected  $module;
    protected  $action;

    protected  $RedisSession;

    /*定义错误代码信息*/
    static public $return_code = [
        //操作信息 1000
        '1001' => '操作成功',
        '1002' => '操作失败',
        '1003' => '登录成功',
        '1004' => '登录失败',
        '1005' => '密码错误',
        '1006' => '注册成功',
        '1007' => '注册失败',
        //请求信息 2000
        '2001' => '参数错误',
        '2002' => '请先登陆',
        '2003' => '授权不符',
        //判断信息 4000
        '4000' => '图形验证码错误',
        '4001' => '账号不存在',
        '4002' => '账号被禁用',
        '4003' => '数据操作失败',
        '4004' => '数据不存在',
        '4005' => '手机号已注册',
        '4006' => '证书编号已注册',
        //短信信息 3000
        '3000' => '发送成功',
        '3001' => '发送失败',
        '3002' => '短信已发送,请勿重复点击',
        '3003' => '短信验证码错误',
        //上传 4000
        '5000' => '上传成功',
        '5001' => '上传失败',
    ];

    public  function __construct(Request $request = null)
    {
        parent::__construct();
        $request = Request::instance();
        $this->regIp = $request->ip();
        $this->controller = $request->controller();
        $this->module = $request->module();
        $this->action = $request->action();
        //session redis 连接
        $this->RedisSession = new RedisSession();
        $this->RedisSession->open('','');


        //检测用户登录
        /*$isCheck =new Check();
        $isCheck->isCheckUser();*/
    }
    /**
     * @param string $code
     * @param array $data
     * @param string $msg
     * @return array
     */
    static public function showReturnCode($code = '', $msg = '')
    {
        $returnData = [
            'code' => '500',
            'msg' => '未定义消息'
        ];
        if (empty($code)) return $returnData;
        $returnData['code'] = $code;
        if(!empty($msg)){
            $returnData['msg'] = $msg;
        }else if (isset(self::$return_code[$code]) ) {
            $returnData['msg'] = self::$return_code[$code];
        }
        return $returnData;
    }

    static public function showReturnCodeMsg($code = '', $msg = '')
    {
        return self::showReturnCode($code,$msg);

    }


}