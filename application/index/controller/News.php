<?php
/*
 * 文章控制器
 * */

namespace app\index\controller;


use think\Controller;
use think\Request;

class News extends Controller
{


    public function index(Request $request = null)
    {

        return $this->fetch("index");
    }

    public function details()
    {

        echo "213123sdfsffsfsfds";

       /* $id = $request->param('id');
        if (empty($id)){
            $this->error('文章不存在1','/index');
            exit();
        }
        $Article = ArticleModel::get($id);
        if (empty($Article)){
            $this->error('文章不存在','/index');
            exit();
        }

        $body = htmlspecialchars_decode($Article->Bodyarticle->body);
        $reslut = db('Articles')->where('id', $id)->find();
        return $this->fetch("index", ['reslut' => $reslut, 'body' => $body]);*/
    }

}