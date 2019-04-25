<?php
/**
 * Created by PhpStorm.
 * User: asus
 * Date: 2018/12/13
 * Time: 19:23
 */

namespace app\common\model;


use think\facade\Cache;
use think\Model;

class User extends Model
{
    protected $pk = 'uid';
    protected $update = ['update_time'];


    /*
     * 检测用户数据Redis是否存在数据
     * */
    protected function checkCacheRedis($uid)
    {
        if(Cache::store('redis')->has('user' . $uid)){
            return true;
        }else{
            return false;
        }
    }

    /*
     * 注册登录查询 使用
     * */
    public function getUser($phone)
    {
        return $this->where('uid', '=', $phone)
            ->field(true)
            ->find();
    }
    /*
     * 查看个人信息使用
     * */
    public function getUserInfo($uid)
    {
        if(Cache::store('redis')->has('user' . $uid)){
            return Cache::store('redis')->get('user'. $uid);
        }else{
            return $this->where('uid', '=', $uid)
                ->field(true)
                ->find();
        }
    }
    /*
     *个人信息
     * return object
     * */
        public function getUserIndex($uid)
        {
            self::curdSessionUser($uid);
            return json_decode(Cache::store('redis')->get('user' . $uid));
        }

    /*
     * 增删改查 缓存用户数据
     * @param uid
     * */
    public function curdSessionUser($uid)
    {
        $result = $this->where('uid', '=', $uid)
            ->field(true)
            ->find();
        Cache::store('redis')->set('user' . $uid, json_encode($result));
    }


}