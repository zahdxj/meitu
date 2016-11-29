<?php

/* 
 * 会员管理
 */
namespace app\admin\controller;
use app\common\controller\Common;

class Member extends Common{
    
    //会员列表
    public function index(){
        $list=db('member')->order('user_id desc')->paginate(20);
        $data=array(
            'list'=>$list,
            'page'=>$list->render()
        );
        $this->assign($data);
        return $this->fetch();
    }
    
}


