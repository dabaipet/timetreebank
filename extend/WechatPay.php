<?php

/**
 *-------------LeSongya--------------
 * Explain:  微信支付
 * File name: WechatPay.php
 * Date: 2018/12/20
 * Author: 王海鹏
 * Project name: 乐送呀
 *-----------------------------------------
 */
class WechatPay
{
    //参数配置
    public $config = array(
        'appid' => "", /*微信开放平台上的应用id*/
        'mch_id' => "", /*微信申请成功之后邮件中的商户id*/
        'api_key' => "", /*在微信商户平台上自己设定的api密钥 32位*/
    );
    //服务器异步通知页面路径(必填)
    public $notify_url = '';
    //商户订单号(必填，商户网站订单系统中唯一订单号)
    public $out_trade_no = '';
    //商品描述(必填，不填则为商品名称)
    public $body = '';
    //付款金额(必填)
    public $total_fee = 0;
    //自定义超时(选填，支持dhmc)
    public $time_expire = '';
    private $WxPayHelper;

    public function App($total_fee,$tade_no)
    {
        $this->total_fee = intval($total_fee * 100);//订单的金额 1元
        $this->out_trade_no = $tade_no;// date('YmdHis') . substr(time(), - 5) . substr(microtime(), 2, 5) . sprintf('%02d', rand(0, 99));//订单号
        $this->body = '乐送呀';//支付描述信息
        $this->time_expire = date('YmdHis', time() + 86400);//订单支付的过期时间(eg:一天过期)
        $this->notify_url = "https://api.lesongya.com/notifyandroid";//异步通知URL(更改支付状态)
        //数据以JSON的形式返回给APP
        $app_response = $this->doPay();
        if (isset($app_response['return_code']) && $app_response['return_code'] == 'FAIL') {
            exit(json_encode(['code' => 202, 'msg' => $app_response['return_msg']]));
        } else {
            /* $responseData = array(
                 'notify_url' => ,
                 'app_response' => $app_response,
             );*/
            exit(json_encode(['code' => 200, 'turl' => $this->notify_url, 'msg' => 'success','response'=> $app_response]));
        }
    }

    /**
     * 异步通知信息验证
     * @return boolean|mixed
     */
    public function verifyNotify()
    {
        $xml = isset($GLOBALS['HTTP_RAW_POST_DATA']) ? $GLOBALS['HTTP_RAW_POST_DATA'] : '';
        if (!$xml) {
            return false;
        }
        $wx_back = xmlToArray($xml);
        if (empty($wx_back)) {
            return false;
        }
        $checkSign = getVerifySign($wx_back, $this->config['api_key']);
        if ($checkSign == $wx_back['sign']) {
            return $wx_back;
        } else {
            return false;
        }
    }
    /**
     * 生成支付(返回给APP)
     * @return boolean|mixed
     */
    private function doPay()
    {
        //检测构造参数
        $this->chkParam();
        return $this->createAppPara();
    }
    /*
     * 检查参数
     * */
    private function chkParam()
    {
        //用户网站订单号
        if (empty($this->out_trade_no)) {
            die('out_trade_no error');
        }
        //商品描述
        if (empty($this->body)) {
            die('body error');
        }
        if (empty($this->time_expire)) {
            die('time_expire error');
        }
        //检测支付金额
        if (empty($this->total_fee) || !is_numeric($this->total_fee)) {
            die('total_fee error');
        }
        //异步通知URL
        if (empty($this->notify_url)) {
            die('notify_url error');
        }
        if (!preg_match("#^https:\/\/#i", $this->notify_url)) {
            $this->notify_url = "https://" . $_SERVER['HTTP_HOST'] . $this->notify_url;
        }
        return true;
    }

    /**
     * APP统一下单
     */
    private function createAppPara()
    {
        $url = "https://api.mch.weixin.qq.com/pay/unifiedorder";
        $data["appid"] = $this->config['appid'];//微信开放平台审核通过的应用APPID
        $data["body"] = $this->body;//商品或支付单简要描述
        $data["mch_id"] = $this->config['mch_id'];//商户号
        $data["nonce_str"] = getRandChar(32);//随机字符串
        $data["notify_url"] = $this->notify_url;//通知地址
        $data["out_trade_no"] = $this->out_trade_no;//商户订单号
        $data["spbill_create_ip"] = get_client_ip();//终端IP
        $data["total_fee"] = $this->total_fee;//总金额
        $data["time_expire"] = $this->time_expire;//交易结束时间
        $data["trade_type"] = "APP";//交易类型
        $data["sign"] = getSign($data, $this->config['api_key']);//签名
        $xml = arrayToXml($data);
        $response = postXmlCurl($xml, $url);
        //将微信返回的结果xml转成数组
        $responseArr = xmlToArray($response);
        if (isset($responseArr["return_code"]) && $responseArr["return_code"] == 'SUCCESS') {
            return $this->getOrder($responseArr['prepay_id']);
        }
        return $responseArr;
    }

    /**
     * 执行第二次签名，才能返回给客户端使用
     * @param int $prepayId :预支付交易会话标识
     * @return array
     */
    public function getOrder($prepayId)
    {
        $data["appid"] = $this->config['appid'];
        $data["noncestr"] = getRandChar(32);
        $data["package"] = "Sign=WXPay";
        $data["partnerid"] = $this->config['mch_id'];
        $data["prepayid"] = $prepayId;
        $data["timestamp"] = time();
        $data["sign"] = getSign($data, $this->config['api_key']);
        $data["packagestr"] = "Sign=WXPay";
        return $data;
    }
}