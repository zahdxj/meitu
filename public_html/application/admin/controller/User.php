<?php

/* 
 * 管理员管理
 */

namespace app\admin\controller;
use app\common\controller\Common;

class User extends Common{
    
    //管理员列表
    public function index(){
        $list=  model('AdminUser')->order("add_time desc")->paginate(10);
        foreach ($list as $k=>$v){
            if($v['user_id'] == 1){
                $list[$k]['group']='超级管理员';
            }else{
                $group=db('auth_group_access ga')->join("h5_auth_group ag","ga.group_id=ag.id")->where("ga.uid=$v[user_id]")->field('ag.title')->find();
                $list[$k]['group']=$group['title'];
            }
        }
        $data = array(
			'list' => $list,
			'page' => $list->render()
		);
        $this->assign($data);
        return $this->fetch();
    }
    
    //添加管理员
    public function add(){
        if(IS_POST){
            $AdminUser= model("AdminUser");
            $data=  $this->param;
            $user_id=$AdminUser->register($data['user_name'],$data['password']);
            if($user_id>0){
                //更新用户登录信息
                $user_info['last_login_ip']    =  get_client_ip();
                $user_info['add_time']  =  time();
                $user_info['last_login_time']  =  time();
                if(!db('admin_user')->where(array('user_id'=>$user_id))->update($user_info)){
                    $this->error('添加管理员失败');
                }else{
                    $this->success('添加管理员成功',url('User/index'));
                }
            }else{
                return $this->error($AdminUser->getError());
            }
        }else{
            $data=array(
                'title'=>'添加管理员',
            );
            $this->assign($data);
            return $this->fetch();
        }
    }

    //编辑管理员
    public function edit(){
        if(IS_POST){
            $AdminUser=model('AdminUser');
            $param=  $this->param;
            $data['user_id']=$param['user_id'];
            $data['user_name']=$param['user_name'];
            $data['password']=$param['password'];
            $result=$AdminUser->editAdminInfo($data,true);
            if($result!==false){
                return $this->success('编辑管理员成功',url('index'));
            }else{
                return $this->error($AdminUser->getError());
            }
        }else{
            $admin_info=  $this->getAdminInfo();
            $data=array(
                'title'=>'编辑管理员',
                'info'=>$admin_info
            );
            $this->assign($data);
            return $this->fetch('add');
        }
    }
    
    //管理员授权
    public function auth(){
        $param=  $this->param;
        if(IS_POST){
            $is_exists=  db('auth_group_access')->where("uid=$param[user_id]")->find();
            if($is_exists){
                $res=db('auth_group_access')->where("uid=$param[user_id]")->setField("group_id",$param['group_id']);
            }else{
                $res=model('AuthGroupAccess')->save(array('uid'=>$param['user_id'],'group_id'=>$param['group_id']));
            }
            if($res!==false){
                return $this->success('授权成功',url('index'));
            }else{
                return $this->error('授权失败');
            }
        }else{
            $auth_group=db("auth_group")->field("id,title")->where("status=1 and module='admin'")->select();
            $user_group=db("auth_group_access")->where("uid=$param[user_id]")->find();
            foreach ($auth_group as $k=>$v){
                if($v['id'] ==$user_group['group_id']){
                    $auth_group[$k]['checked']=true;
                }
            }
            $data=array(
                'title'=>'管理员授权',
                'info'=>$auth_group,
                'user_id'=>$param['user_id']
            );
            $this->assign($data);
            return $this->fetch();
        }
    }

    //获取管理员信息
    public function getAdminInfo(){
        $param=  $this->param;
        if($param['user_id'] > 0){
            $admin_info=model('AdminUser')->where(array('user_id'=>$param['user_id']))->find();
            if(!$admin_info){
                $this->error('此用户不存在');
            }
            return $admin_info;
        }else{
          return  $this->error('此用户不存在');
        }
    }

    public function delete(){
        $user_id=  input('get.user_id');
		$res=model('AdminUser')->where(array('user_id' => $user_id))->delete();
        if($res){
            return $this->success('删除管理员成功！');
        }else{
            return $this->error('删除管理员失败！');
        }
    }
    
}