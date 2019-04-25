<?php
/**
 *-------------LeSongya--------------
 * Explain: 注册登录
 * File name: Signin.php
 * Date: 2018/12/10
 * Author: 王海鹏
 * Project name: 乐送呀
 *-----------------------------------------
 */

namespace app\api\controller;

use think\facade\Cache;
use think\facade\Session;
use app\common\model\User;
class Signin extends SignBase
{
    /*
     * 前置操作
     * */
    protected $middleware = [
        //'Auth' 	=> ['except' 	=> ['hello'] ],
        // 'Hello' => ['only' 		=> ['index'] ],
    ];

    /*
     * 注册账号：
     * @param   phone   手机号码
     * @param   code    验证码
     * */
    public function index()
    {
        $phone = $this->request->param('phone');
        $code = $this->request->param('code');
        Session::set($phone . 'sms', $code);
        $result = $this->validate(['phone' => $phone, 'code' => $code], 'app\api\validate\User.signin');
        if (true !== $result) {
            return json(['code' => '202', 'msg' => $result]);
        }
        if (Session::get($phone . 'sms') != $code) {
            return json(['code' => '202', 'msg' => showReturnCode('3003')]);
        }
        $user = new User();
        $userResult = $user->getUser($phone);
        if (empty($userResult)) {
            $token = $this->request->token('token', 'sha1');
            //过滤非数据表字段
            $user->allowField(true)->save([
                'phone' => $phone,
                'token' => $token,
            ]);
            Session::set('user' . $user->uid, $user::get($user->uid)->toJson());
            $uidVble = $user->uid;
        } else {
            Session::set('user' . $userResult->uid, $userResult->toJson());
            $uidVble = $userResult->uid;
        }
        $userSession = json_decode(Session::get('user' . $uidVble));
        //账户状态
        switch ($userSession->status) {
            case 2:
                return json(['code' => '202', 'msg' => showReturnCode('4000')]);
                break;
            case 3:
                return json(['code' => '202', 'msg' => showReturnCode('4002')]);
                break;
        }
        $user->isUpdate(true, ['uid' => $userSession->uid])->save(['inc' => ['inc', 1]]);
        return json(['code' => '200', 'uid' => $userSession->uid, 'token' => $userSession->token, 'turl' => url('/user/choice'), 'msg' => showReturnCode('5000')]);
    }

    /*
     * 第三方登录(微信)
     * */
    public function thirdParty()
    {

    }
}