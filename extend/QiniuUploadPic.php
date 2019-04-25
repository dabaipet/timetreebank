<?php
require_once  dirname(__DIR__) . '/vendor/autoload.php';
/**
 *-------------LeSongya--------------
 * Explain: 七牛云存储
 * File name: QiniuUploadPic.php
 * Date: 2019/2/20
 * Author: 王海鹏
 * Project name: 乐送呀
 *-----------------------------------------
 */
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
class QiniuUploadPic
{
    protected $accessKey = 'IMYsTotd2x68gdty22MvxRY-YKe03Ls36HFNaG3p';
    protected $secretKey = 'IwmkHiCUlmMW2dSzWjRdvvKbpOzQyUq4iPfPQgaY';
    protected $auth;
    protected $bucket = 'lesongya-user-images';

    public function __construct()
    {
        $this->auth = new Auth($this->accessKey, $this->secretKey);
    }
    /*
     * 上传类
     * */
    public function UploadManager($FileNewName,$filePath){

        $uploadMgr = new UploadManager();
        $token = $this->auth->uploadToken($this->bucket);
        // 上传文件到七牛
        list($ret, $err) = $uploadMgr->putFile($token, $FileNewName, $filePath);
        echo "\n====> putFile result: \n";
        if ($err !== null) {
            var_dump($err);
        } else {
            var_dump($ret);
        }

    }


}