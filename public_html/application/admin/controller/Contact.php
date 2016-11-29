<?php

/* 
 * 客服管理
 */
namespace app\admin\controller;
use app\common\controller\Common;

class Contact extends Common{
    
    //客服列表
    public function contactList(){
        $contact_list=db('contact')->select();
        $this->assign("list",$contact_list);
        return $this->fetch();
    }
    
    //添加客服
    public function addContact(){
        if(IS_POST){
            $Contact=model('Contact');
            $data=  $this->param;
            $contact_id=$Contact->add($data);
            if($contact_id >0){
                $file = $this->request->file('weixin_img');
                if($file){
                    // 移动到框架应用根目录/public/uploads/ 目录下
                    $info = $file->move(ROOT_PATH . 'public/uploads/contact/');
                    if($info){
                        // 成功上传后 获取上传信息
                        // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
                        $filename=$info->getFilename();
                        db('contact')->where("contact_id=$contact_id")->update(array('weixin_img'=>'uploads/contact/'.date('Ymd').'/'.$filename));
                    }else{
                        // 上传失败获取错误信息
                        echo $file->getError();
                    }
                }
                return $this->success('添加客服成功',url('contactList'));
            }else{
                return $this->error($Contact->getError());
            }
        }else{
            $this->assign("title",'添加客服');
            return $this->fetch();
        }
    }
    
    //编辑客服
    public function editContact(){
        $datas=  $this->param;
        $contact_id=$datas['contact_id'];
        if(IS_POST){
            $Contact=model('Contact');
            $result=$Contact->edit($datas);
            if($result!==false){
                $file = $this->request->file('weixin_img');
                if($file){
                    // 移动到框架应用根目录/public/uploads/ 目录下
                    $info = $file->move(ROOT_PATH . 'public/uploads/contact/');
                    if($info){
                        // 成功上传后 获取上传信息
                        // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
                        $filename=$info->getFilename();
                        $origin_file=db('contact')->where("contact_id=$contact_id")->field('weixin_img')->find();
                        if($origin_file['weixin_img']){
                            @unlink(@unlink(ROOT_PATH . 'public/uploads/contact/'.date('Ymd').'/'.$origin_file['weixin_img']));
                        }
                        db('contact')->where("contact_id=$contact_id")->update(array('weixin_img'=>'uploads/contact/'.date('Ymd').'/'.$filename));
                    }else{
                        // 上传失败获取错误信息
                        echo $file->getError();
                    }
                }
                
                $this->success('编辑客服成功',url('contactList'));
            }else{
                return $this->error($Contact->getError());
            }
        }else{
            $contact_info=db('contact')->where("contact_id=$contact_id")->find();
            $data=array(
                'info'=>$contact_info,
                'title'=>'编辑客服'
            );
            $this->assign($data);
            return $this->fetch('addContact');
        }
    }
    
    //删除客服
    public function delContact(){
        $datas=  $this->param;
        $contact_id=$datas['contact_id'];
        $contact_info=db('contact')->where("contact_id=$contact_id")->find();
        $result=db('contact')->where("contact_id=$contact_id")->delete();
        if($result){
            @unlink(ROOT_PATH . 'public/'.$contact_info['weixin_img']);
            return $this->success('删除客服成功');
        }else{
            return $this->error('删除客服失败');
        }
    }
    
}

