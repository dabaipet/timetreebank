<?php
/**
 *-------------LeSongya--------------
 * Explain:支付控制器
 * File name: Pay.php
 * Date: 2018/12/20
 * Author: 王海鹏
 * Project name: 乐送呀
 *-----------------------------------------
 */

namespace app\api\controller;


use think\App;
//use app\common\model\Pay as PayM;
use app\common\model\Order;

class Pay extends Apibase
{
//调用统一下单接口生成预支付订单并把数据返回给APP
    public function wechatApp()
    {
        $param = $this->request->param(); //接收值

        $tade_no = $param['orderCode'];
        $order = new Order(); //实例化订单
        $result = $order->getOrderNum($tade_no); //查询订单信息
        $total_fee = $result->money; //订单总金额

        $wxpayandroid = new \WechatPay();  //实例化微信支付类
        $wxpayandroid->App($total_fee, $tade_no); //调用weixinpay方法

    }

    //异步通知接口
    public function wechatNotify()
    {
        $wxpayandroid = new \WechatPay();  //实例化微信支付类
        $verify_result = $wxpayandroid->verifyNotify();
        if ($verify_result['return_code'] == 'SUCCESS' && $verify_result['result_code'] == 'SUCCESS') {
            //商户订单号
            $out_trade_no = $verify_result['out_trade_no'];
            //交易号
            $trade_no = $verify_result['transaction_id'];
            //交易状态
            $trade_status = $verify_result['result_code'];
            //支付金额
            $total_fee = $verify_result['total_fee'] / 100;
            //支付过期时间
            $pay_date = $verify_result['time_end'];
            $order = new Order();
            $result = $order->getOrderNum($out_trade_no); //获取订单信息
            $total_amount = $result['money'];
            if ($total_amount == $total_fee) {
                // 验证成功 修改数据库的订单状态等 $result['out_trade_no']为订单号
                //此处写自己的逻辑代码
            }
            exit('<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>');
        } else {
            exit('<xml><return_code><![CDATA[FAIL]]></return_code><return_msg><![CDATA[ERROR]]></return_msg></xml>');
        }
    }
}