<?php
/**
 * 钱包模型.
 * User: whp
 * Date: 2018/10/24
 * Time: 23:50
 */

namespace app\common\model;

use think\Model;
use think\facade\Cache;

class Wallet extends Model
{

    public function getWallet(){

    }
    public function getWalletInfo(){

    }
    /*
     * 总金额：充值金额，赠送金额
     * */
    public function getWalletNum($uid){
        return $wallet = $this->where('uid', '=', $uid)
            ->field('give_m,recharge_m')
            ->find();
    }
    /*
     * 增删改查缓存数据
     * @param uid
     * */
    public function curdCacheWallet($uid)
    {
        $result = $this->where('uid', '=', $uid)
            ->field(true)
            ->find();
        Cache::store('redis')->set('wallet' . $uid, json_encode($result));
    }
}