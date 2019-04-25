<?php
/**
 *-------------LeSongya--------------
 * Explain: 用户控制器
 * File name: User.php
 * Date: 2018/12/5
 * Author: 王海鹏
 * Project name: 乐送呀
 *------------------------------------
 */

namespace app\api\controller;

use app\common\model\PropertyGps;
use app\common\model\User as UserM;
use app\common\model\Wallet;
use app\common\model\Order;
use think\facade\Cache;
use think\facade\Session;

class User extends Apibase
{
    /*
     * 基本信息
     * @params  user表    头像 手机号 分数 金额 我的订单数
     * @params  wallet表  钱包余额
     * @params  order表  订单数
     * */
    public function index()
    {
        $user = new UserM();
        $userResult = $user->getUserIndex($this->uid);
        $order = new Order();
        $orderResult = $order->getOrderNumber($this->uid, $this->identity);
        $wallet = new Wallet();
        $walletMoney = $wallet->getWalletNum($this->uid);
        $money = empty($walletMoney) ? 0 : $walletMoney->give_m + $walletMoney->recharge_m;
        return json(['code' => 200, 'phone' => $userResult->phone, 'order' => $orderResult, 'wallet' => $money]);
    }

    /*
     * 个人信息
     * @return  头像  姓名  是否实名认证 手机号  性别  （微信 QQ） 是否绑定
     * */
    public function info()
    {
        $user = new UserM();
        $userResult = $user->getUserInfo($this->uid);
        return json(['code' => 200, 'user' => $userResult]);

    }

    /*
     * 头像设置
     * */
    public function setHeadpic()
    {
        $file = $this->request->file('pic');
       // $info = $file->validate(['size'=>15678,'ext'=>'jpg,png,gif'])->move( '../uploads');
        $msg = $this->validate(['variable' => $file], 'app\api\validate\User.variable');
        if ($msg !== true) {
            exit(json_encode(['code' => '202', 'msg' => 'pic' . $msg]));
        }
        if($file){
            //$ext = pathinfo($file->getInfo('name'), PATHINFO_EXTENSION);
            //$FileNewName =substr(md5($file->getRealPath()) , 0, 5). date('YmdHis') . rand(0, 9999) . '.' . $ext;
            $imageView = 'imageView2/1/w/200/h/200/q/75|imageslim';//图片样式
            $updoke = new \QiniuUploadPic();
            $updoke ->UploadManager($file->getFilename(),$file->getRealPath());
            $pic = 'http://pn7xldr19.bkt.clouddn.com'.$file->getFilename().'?'. $imageView;
            //调用图片上传接口
            $user = new UserM();
            $result = $user->isUpdate(true, ['uid' => $this->uid])->save(['head_pic' => $pic]);
            if ($result) {
                $user->curdSessionUser($this->uid);
                return json(['code' => 200, 'msg' => showReturnCode('1001')]);
            } else {
                return json(['code' => 202 , 'msg' => showReturnCode('1002')]);
            }
        }
        return json(['code' => 202, 'msg' => showReturnCode('1009')]);
    }

    /*
     * 实名认证
     * @param name 真实姓名
     * @param idnum 身份证号
     * @param idpic 身份证照片
     * @param validty 有效期
     * */
    public function realName()
    {
        $name = $this->request->param('name');
        $idNum = $this->request->param('idnum');
        $idPic = $this->request->param('idpic');
        $validty = $this->request->param('validty');
        //参数校验
        $msg = $this->validate(['name' => $name, 'idNum' => $idNum, 'idPic' => $idPic, 'validty' => $validty], 'app\api\validate\User.set');
        if ($msg != true) {
            exit(json_encode(['code' => '202', 'msg' => $msg]));
        }
        $user = new UserM();
        $result = $user->isUpdate('true', ['uid' => $this->uid])->save(['name' => $name, 'id_pic' => $idPic, 'id_num' => $idNum, 'validty' => $validty]);
        if ($result) {
            $user->curdSessionUser($this->uid);
            return json(['code' => 200]);
        } else {
            return json(['code' => 202]);
        }
    }

    /*
     * 设置手机号
     * @param code 手机验证码
     * @param newPhone 新手机号
     * */
    public function setPhone()
    {
        $newPhone = $this->request->param('newphone');
        $code = $this->request->param('code');
        $msg = $this->validate(['phone' => $newPhone, 'code' => $code], 'app\api\validate\User.set');
        if ($msg !== true) {
            exit(json_encode(['code' => '202', 'msg' => $msg]));
        }
        if (Session::get($newPhone . 'sms') != $code) {
            exit(json_encode(['code' => '202', 'msg' => showReturnCode('3003')]));
        }
        $user = new UserM();
        $result = $user->isUpdate(true, ['uid' => $this->uid])->save(['phone' => $newPhone]);
        if ($result) {
            $user->curdSessionUser($this->uid);
            return json(['code' => '200', 'msg' => showReturnCode('1021')]);
        }

    }

    /*
     * 设置性别
     * @param sex 性别
     * */
    public function setSex()
    {
        $sex = $this->request->param('sex');
        $msg = $this->validate(['sex' => $sex], 'app\api\validate\User.set');
        if ($msg !== true) {
            exit(json_encode(['code' => '202', 'msg' => $msg]));
        }
        $user = new UserM();
        $result = $user->isUpdate(true, ['uid' => $this->uid])->save(['sex' => $sex]);
        if ($result) {
            $user->curdSessionUser($this->uid);
            return json(['code' => '200', 'msg' => showReturnCode('1021')]);
        }
    }


    /*
     * 物业设置存放点信息
     * */
    public function setDeposit()
    {
        $name = $this->request->param('name');
        $lat = $this->request->param('lat');
        $long = $this->request->param('long');
        $property = new PropertyGps();
        $property->save(['uid' => $this->uid, 'name' => $name, 'lat' => $lat, 'long' => $long]);

    }

    /*
     * 获取GPS 返回 方圆3公里所有站点
     * */
    public function getGps()
    {
        $lng = $this->request->param('lng');
        $lat = $this->request->param('lat');

    }


    /*
     * 用户选择身份
     * @param   identity    身份标识 1骑手 2快递 3物业 4个人
     * */
    public function choice()
    {
        $identity = $this->request->param('identity');
        $result = $this->validate(['identity' => $identity], 'app\api\validate\User.choice');
        if (true !== $result) {
            return json(['code' => '202', 'msg' => $result]);
        }
        $UserInfo = new UserM();
        $UserInfoResult = $UserInfo->allowField('identity')->save(['identity' => $identity], ['uid' => $this->uid]);
        if ($UserInfoResult == true) {
            Session::set('identity' . $this->uid, $identity);
            return json(['code' => '200', 'turl' => url('/location'), 'msg' => showReturnCode('1020')]);
        }
    }

    /*
     * 绑定微信
     * */
    public function wechat()
    {

    }

}