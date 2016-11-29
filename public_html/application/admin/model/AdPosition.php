<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace app\admin\model;
use app\common\model\Base;

class AdPosition extends Base{
    protected $name='ad_position';
    
    public function add($data){
        $result=  $this->validate(true)->allowField(true)->save($data);
        if($result!==false){
            return $this->data['position_id'];
        }else{
            return false;
        }
    }
    
    public function edit($data){
        if($data['position_id']>0){
            $result=  $this->validate(true)->allowField(true)->save($data,array('position_id'=>$data['position_id']));
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