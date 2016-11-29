<?php
namespace app\home\controller;
use app\common\controller\Home;

class Index extends Home
{
    public function index()
    {
        //首页轮播图
        $banner_list=db('banner')->where("is_show=1")->order('id')->select();
        $this->assign("banner_list",$banner_list);
        //首页广告
        $ad_list1=db('ad')->where("position_id=3 and is_show=1")->find();
        $ad_list2=db('ad')->where("position_id=5 and is_show=1")->find();
        $ad_list3=db('ad')->where("position_id=6 and is_show=1")->find();
        $this->assign("ad_list1",$ad_list1);
        $this->assign("ad_list2",$ad_list2);
        $this->assign("ad_list3",$ad_list3);
        //首页展示商品列表
        $goods_list=db('goods')->where("is_show_index=1 and is_on_sale=1")->order('sort')->select();
        $this->assign("goods_list",$goods_list);
        return $this->fetch();
    }
    
   
}
