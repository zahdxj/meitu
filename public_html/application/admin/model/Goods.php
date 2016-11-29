<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace app\admin\model;
use app\common\model\Base;

class Goods extends Base{
    protected $name='goods';
    
    public function add($data){
        $result=  $this->validate(true)->allowField(true)->save($data);
        if($result!==false){
            return $this->data['goods_id'];
        }else{
            return false;
        }
    }
    
    public function edit($data){
        if($data['goods_id']>0){
            $result=  $this->validate(true)->allowField(true)->save($data,array('goods_id'=>$data['goods_id']));
            if($result!==false){
                return true;
            }else{
                return false;
            }
        }else{
            $this->error='非法操作';
            return false;
        }
    }
}