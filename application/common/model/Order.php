<?php
/**
 *-------------LeSongya--------------
 * Explain:
 * File name: Order.php
 * Date: 2018/12/6
 * Author: 王海鹏
 * Project name: 乐送呀
 *-----------------------------------------
 */

namespace app\common\model;


use think\Model;
use think\facade\Cache;

class Order extends Model
{

    /*
     * 类别 1骑手 2快递 3物业 4个人
     * */
    /*
     * 订单数量
     * @param   UID 用户UID
     * @param   identity    用户身份标识
     * @return  数量
     * */
    public function getOrderNumber($uid,$identity){
        return $this->where(['uid' => $uid,'is_identity' => $identity])
            ->count('id');
    }
    /*
     *
     * */
    public function getOrderView($id,$uid){
        return $this->where(['id' => $id, 'uid' => $uid])
            ->field(true)
            ->find();
    }
    /*
     * 订单信息
     * */
    public function getOrder($uid,$identity){
        return $this->where(['uid' => $uid,'is_identity' => $identity])
            ->field(true)
            ->find();
    }
    /*
     * 订单号查询信息
     * */
    public function getOrderNum($number){
        return $this->where(['number' => $number])
            ->field('uid,money,o_status,f_status,is_show')
            ->find();
    }
    /*
     * 增删改查 缓存订单数据
     * @param uid
     * */
    public function curdOrder($uid)
    {
        $result = $this->where('uid', '=', $uid)
            ->field(true)
            ->select();
        Cache::store('redis')->set('order' . $uid, json_encode($result));
    }
    /*
     * 增删改查 缓存单条订单数据
     * */
    public function curdOrderOne(){

    }
}