<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace app\admin\model;
use app\common\model\Base;

class Contact extends Base{
    protected $name='contact';
    
    public function add($data){
        $result=  $this->validate(true)->allowField(true)->save($data);
        if($result!==false){
            return $this->data['contact_id'];
        }else{
            return false;
        }
    }
    
    public function edit($data){
        if($data['contact_id']>0){
            $result=  $this->validate(true)->allowField(true)->save($data,array('contact_id'=>$data['contact_id']));
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