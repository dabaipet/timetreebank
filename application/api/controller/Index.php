<?php
/**
 *-------------LeSongya--------------
 * Explain: APP index
 * File name: Index.php
 * Date: 2018/11/30
 * Author: 王海鹏
 * Project name: 乐送呀
 *-----------------------------------------
 */
namespace app\api\controller;

use app\common\model\PropertyGps;

class Index extends SignBase
{
    /*
     * 获取当前定位周边信息
     * @param   long 经度
     * @param   lat 纬度
     * */
    public function location()
    {
        //获取当前经纬度
        $long = $this->request->param('long');
        $lat = $this->request->param('lat');
        $result = $this->validate(['long' => $long, 'lat' => $lat], 'app\api\validate\Gps');
        if (true !== $result) {
            return json(['code' => '202', 'msg' => $result]);
        }
        //计算周围可以存放地点
        $gpsAround = new PropertyGps();
        $aroundAddress = $gpsAround->getDistance("$long","$lat");
        return json(['ss'=>$aroundAddress]);
    }
    /*
     * 定位当前位置
     * */
    public function present(){

    }
}
