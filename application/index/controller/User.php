<?php
namespace app\index\controller;

/*
 * 用户中心
 * @ index
 * @ myOrder
 * @ myCoupons
 * @ myRefund
 * @ myEvaluate
 * @ collectionBaby
 * @ collectionDoc
 * @ systemMessage
 * @ myRelease
 * @ mySafe
 * @ myPassword
 * */
use think\Controller;
class User extends  Controller
{
    #用户中心
    public function  index()
    {
        return $this->fetch("index");

    }
    #订单
    public function  myOrder()
    {
        return $this->fetch("myorder");

    }
    #优惠券
    public function  myCoupons()
    {
        return $this->fetch("mycoupons");

    }
    #售后
    public function  myRefund()
    {
        return $this->fetch("myrefund");

    }
    #评价
    public function  myEvaluate()
    {
        return $this->fetch("myevaluate");

    }
    #收藏
    public function  collectionBaby()
    {
        return $this->fetch("collectionbaby");

    }
    #收藏的医生和医院
    public function  collectionDoc()
    {
        return $this->fetch("collection");

    }

    /*
     * 系统消息
     * */
    public function  systemMessage()
    {
        return $this->fetch("systemessage");

    }

    /*
     * 我的发布
     * */
    public function  myRelease()
    {
        return $this->fetch("myrelease");

    }

    /*
     * 收藏的帖子
     * */
    public function  myPost()
    {
        return $this->fetch("mypost");

    }
    /*
     * 社区消息
     * */
    public function  bbsMessage()
    {
        return $this->fetch("bbsmessage");

    }

    /*
     *修改资料
     * */
    public function  myModify()
    {
        return $this->fetch("mymodify");

    }

    /*
     *安全中心
     * */
    public function  mySafe()
    {
        return $this->fetch("mysafe");

    }

    /*
     *修改密码
     * */
    public function  myPassword()
    {
        return $this->fetch("mypassword");

    }




}
