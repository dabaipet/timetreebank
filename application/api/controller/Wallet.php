<?php
/**
 *-------------LeSongya--------------
 * Explain: 钱包控制器
 * File name: Wallet.php
 * Date: 2018/12/17
 * Author: 王海鹏
 * Project name: 乐送呀
 *-----------------------------------------
 */

namespace app\api\controller;

use app\common\model\Wallet as WalletM;
use think\facade\Cache;

class Wallet extends Apibase
{
    /*
     * 钱包首页
     * @return  余额
     * */
    public function index(){
        $wallet = new WalletM();
        if (Cache::store('redis')->has('wallet'.$this->uid) == false){
            $wallet->curdCacheWallet($this->uid);
        }
        $result = Cache::store('redis')->get('wallet'.$this->uid);
        return json(['code' =>200,'data' => json_decode($result)]);
    }
    /*
     * 余额页面显示
     * @return  余额 充值余额
     * */
    public function balance(){

    }
    /*
     * 我的红包
     * @return  红包金额
     * */
    public function redPackets(){

    }
    /*
     * 微信免密支付
     * @return 跳转微信免密支付协议
     * */
    public function wxSecretfree(){

    }
    /*
     * 月卡
     * */
    public function mealCard(){

    }
    /*
     * 充值
     * */
    public function recharge(){

    }
    /*
     * 支付
     * */
    public function pay(){

    }
    /*
     * 异步通知
     * */
    public function asynchr(){

    }
    /*
     * 同步通知
     * */
    public function synchr(){

    }
    /*
     *
     * 提现
     * */
    public function cash(){

    }
}