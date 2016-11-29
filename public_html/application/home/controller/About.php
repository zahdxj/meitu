<?php

/* 
 * 关于我们
 */
namespace app\home\controller;
use app\common\controller\Home;

class About extends Home{
    
    public function index(){
        return $this->fetch();
    }
    
    public function aboutus(){
        return $this->fetch();
    }
    
}

