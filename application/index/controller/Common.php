<?php

namespace app\index\controller;

use think\Request;
class Common extends Base
{
    public function uploadPro($file, $filetype) {
        $ext = '';
        if ($filetype =='files') {
            $ext = 'pdf,doc,docx';
        } else if ($filetype =='images') {
            $ext = 'jpg,png,gif,jpeg';
        }
        $info = $file->validate(['size'=>10485760, 'ext'=>$ext])
            ->rule('date')
            ->move(ROOT_PATH . 'public' . DS . 'uploads'  . DS . "$filetype", true, false);
        return $info;
    }
    // 上传图片
    public function uploadImage(Request $request=null)
    {
        if ($request->isAjax()) {
            // 获取表单上传文件
            $file = request()->file('image');
            // 移动到框架应用根目录/public/uploads/images/ 目录下
            if ($file) {
                $info = self::uploadPro($file, 'images');
                if ($info) {
                    // 成功上传后 获取上传信息
                    $source = '/uploads/images/' . $info->getSaveName();
                    $codeMsg = $this->showReturnCodeMsg('5000');
                    return ['code' => 200, 'msg' => "$codeMsg[msg]", 'src' => "$source"];
                } else {
                    // 上传失败获取错误信息
                    $error = $file->getError();
                    $codeMsg = $this->showReturnCodeMsg('5001');
                    return ['code' => 202, 'msg' => "$codeMsg[msg]", 'src' => "$error"];
                }
            }
        }
    }
}