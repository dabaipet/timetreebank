<?php
/**
 *-------------LeSongya--------------
 * Explain:
 * File name: Classify.php
 * Date: 2019/5/29
 * Author: 王海鹏
 * Project name: 乐送呀
 *-----------------------------------------
 */

namespace app\index\controller;


class Classify extends Base
{

    public function index(){
        echo "11";
    }
    /*
     * params
     * $cid 分类ID
     * $state   分类
     * $price   价格（免费和时间币）
     * $time    发布时间
     * $areas   地区
     */
    public function read($cid = '0',$state = '0',$price = '0',$time = '0',$areas = '0'){
        //0 = 条件不限 全部展出

    }
}