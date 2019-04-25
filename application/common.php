<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
// 返回Code代码
function showReturnCode($code = '', $msg = '')
{
    $CodeData = [
        //操作信息 1000
        '1001' => '设置成功',
        '1002' => '设置失败',
        '1005' => '密码错误',
        '1006' => '注册成功',
        '1007' => '注册失败',
        '1008' => '上传成功',
        '1009' => '上传失败',
        '1018' => '取消成功',
        '1019' => '取消失败',
        '1020' => '选择成功',
        '1021' => '设置成功',
        //请求信息 2000
        '2001' => '参数错误',
        '2002' => '请先登录',
        '2003' => '授权不符',
        '2004' => '更新成功',
        '2005' => '更新失败',
        '2006' => '非法数据,重新登录',
        '2007' => '下单失败',
        '2008' => '下单成功',
        //判断信息 4000
        '4000' => '账户已注销,请重新注册',
        '4002' => '账号被禁用,请重新注册',
        '4003' => '操作失败',
        '4004' => '数据不存在',
        '4005' => '手机号已注册',
        '4006' => '操作成功',
        //短信信息 3000
        '3000' => '发送成功',
        '3001' => '发送失败',
        '3002' => '短信已发送,请勿重复点击',
        '3003' => '验证码错误',
        //登录判断
        '5000' => '登录成功',
        '5001' => '登录失败',
        '5002' => '用户名已存在',
        '5004' => '退出成功',
        '5005' => '登录超时',
    ];
    if (!empty($msg)) {
        return $msg;
    } elseif (!empty($code) && isset($CodeData[$code])) {
        return $CodeData[$code];
    } else {
        return "未定义消息";
    }
}

/*********************  微信函数列表 ***************************/
/*
 *获取签名
 * @params  $data 签名数据
 * @param   $key key
 * */
function getVerifySign($data, $key)
{
    $String = formatParameters($data, false);
    //签名步骤二：在string后加入KEY
    $String = $String . "&key=" . $key;
    //签名步骤三：MD5加密
    $String = md5($String);
    //签名步骤四：所有字符转为大写
    $result = strtoupper($String);
    return $result;
}
/*
 * 字典排序
 * @params  $paramMap   数组
 * */
function formatParameters($paraMap, $urlencode = false)
{
    $buff = "";
    ksort($paraMap);
    foreach ($paraMap as $k => $v) {
        if ($k == "sign") {
            continue;
        }
        if ($urlencode) {
            $v = urlencode($v);
        }
        $buff .= $k . "=" . $v . "&";
    }
    $reqPar = '';
    if (strlen($buff) > 0) {
        $reqPar = substr($buff, 0, strlen($buff) - 1);
    }
    return $reqPar;
}

/**
 * 得到签名
 * @param object $obj
 * @param string $api_key
 * @return string
 */
function getSign($obj, $api_key)
{
    foreach ($obj as $k => $v) {
        $Parameters[strtolower($k)] = $v;
    }
    //签名步骤一：按字典序排序参数
    ksort($Parameters);
    $String = formatBizQueryParaMap($Parameters, false);
    //签名步骤二：在string后加入KEY
    $String = $String . "&key=" . $api_key;
    //签名步骤三：MD5加密
    $result = strtoupper(md5($String));
    return $result;
}

/**
 * 获取指定长度的随机字符串
 * @param int $length
 */
function getRandChar($length)
{
    $str = null;
    $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
    $max = strlen($strPol) - 1;
    for ($i = 0; $i < $length; $i++) {
        $str .= $strPol[rand(0, $max)];//rand($min,$max)生成介于min和max两个数之间的一个随机整数
    }
    return $str;
}

/**
 * 数组转xml
 * @param array $arr
 * @return string
 */
function arrayToXml($arr)
{
    $xml = "<xml>";
    foreach ($arr as $key => $val) {
        if (is_numeric($val)) {
            $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
        } else
            $xml .= "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
    }
    $xml .= "</xml>";
    return $xml;
}

/**
 * 以post方式提交xml到对应的接口url
 * @param string $xml 需要post的xml数据
 * @param string $url url
 * @param bool $useCert 是否需要证书，默认不需要
 * @param int $second url执行超时时间，默认30s
 * @throws
 */
function postXmlCurl($xml, $url, $second = 30, $useCert = false, $sslcert_path = '', $sslkey_path = '')
{
    $ch = curl_init();
    //设置超时
    curl_setopt($ch, CURLOPT_TIMEOUT, $second);
    curl_setopt($ch, CURLOPT_URL, $url);
    //设置header
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    //要求结果为字符串且输出到屏幕上
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    if ($useCert == true) {
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);//严格校验
        //设置证书
        //使用证书：cert 与 key 分别属于两个.pem文件
        curl_setopt($ch, CURLOPT_SSLCERTTYPE, 'PEM');
        curl_setopt($ch, CURLOPT_SSLCERT, $sslcert_path);
        curl_setopt($ch, CURLOPT_SSLKEYTYPE, 'PEM');
        curl_setopt($ch, CURLOPT_SSLKEY, $sslkey_path);
    }
    //post提交方式
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
    //运行curl
    $data = curl_exec($ch);
    //返回结果
    if ($data) {
        curl_close($ch);
        return $data;
    } else {
        $error = curl_errno($ch);
        curl_close($ch);
        return false;
    }
}

/**
 * 获取当前服务器的IP
 */
function get_client_ip()
{
    if (isset($_SERVER['REMOTE_ADDR'])) {
        $cip = $_SERVER['REMOTE_ADDR'];
    } elseif (getenv("REMOTE_ADDR")) {
        $cip = getenv("REMOTE_ADDR");
    } elseif (getenv("HTTP_CLIENT_IP")) {
        $cip = getenv("HTTP_CLIENT_IP");
    } else {
        $cip = "127.0.0.1";
    }
    return $cip;
}

/**
 * 将数组转成uri字符串
 * @param array $paraMap
 * @param bool $urlencode
 * @return string
 */
function formatBizQueryParaMap($paraMap, $urlencode)
{
    $buff = "";
    ksort($paraMap);
    foreach ($paraMap as $k => $v) {
        if ($urlencode) {
            $v = urlencode($v);
        }
        $buff .= strtolower($k) . "=" . $v . "&";
    }
    $reqPar = '';
    if (strlen($buff) > 0) {
        $reqPar = substr($buff, 0, strlen($buff) - 1);
    }
    return $reqPar;
}

/**
 * XML转数组
 * @param $xml
 * @return mixed
 */
function xmlToArray($xml)
{
    //将XML转为array
    $array_data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
    return $array_data;
}

