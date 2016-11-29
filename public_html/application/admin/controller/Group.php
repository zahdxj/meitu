<?php

/* 
 * 用户组管理
 */
namespace app\admin\controller;
use app\common\controller\Common;

class Group extends Common{
    
    //用户组列表
    public function index(){
        $list=db("auth_group")->where("module='admin'")->order('id desc')->paginate(15);
        $data=array(
            'list'=>$list,
            'page'=>$list->render()
        );
        $this->assign($data);
        return $this->fetch();
    }
    
    //添加用户组
    public function add(){
        if(IS_POST){
            $AuthGroup=  model('AuthGroup');
            $data=  input('post.');
            $data['module']='admin';
            $data['type']=1;
            $is_exists=db('auth_group')->where("module='admin' and title='".$data['title']."'")->find();
            if($is_exists){
                return $this->error('该用户组已存在');
            }
            $res=$AuthGroup->allowField(true)->save($data);
            if($res){
                return $this->success('添加用户组成功',url('index'));
            }else{
                return $this->error($AuthGroup->getError());
            }
        }else{
            $data['title']='添加用户组';
            $this->assign($data);
            return $this->fetch();
        }
    }
    
    //编辑用户组
    public function edit(){
        $AuthGroup=  model('AuthGroup');
        $datas=  $this->param;
        if(IS_POST){
            $arr['title']=$datas['title'];
            $arr['description']=$datas['description'];
            $arr['status']=$datas['status'];
            $result=$AuthGroup->save($arr,array("id"=>$datas['id']));
            if($result!==false){
                return $this->success('编辑用户组成功',url('index'));
            }else{
                return $this->error($AuthGroup->getError());
            }
            
        }else{
            $groupinfo=db('auth_group')->where("id=$datas[id]")->find();
            $data=array(
                'title'=>'编辑用户组',
                'info'=>$groupinfo
            );
            $this->assign($data);
            return $this->fetch('add');
            
        }
    }
    
    //删除用户组
    public function del(){
        $data=  $this->param;
        if($data['id']){
            $res=db('auth_group')->where("id=$data[id]")->delete();
            if($res){
                return $this->success('删除用户组成功',url('index'));
            }else{
                return $this->error('删除用户组失败');
            }
        }else{
            return $this->error('非法操作');
        }
    }
    
    //用户组授权
    public function auth(){
        $params=  $this->param;
        $id=$params['id'];
        if(!$id){
            return $this->error('非法操作');
        }
        if(IS_POST){
            $rule=  $this->request->post('rule/a',array());
            if($rule){
                $rules=  implode(',', $rule);
                $res= db('auth_group')->where("id=$id")->setField("rules",$rules);
                if($res!==false){
                    return $this->success('授权成功',url('index'));
                }else{
                    return $this->error('授权失败');
                }
            }
            
            
        }else{
            $group=db('auth_group')->where("id=$id")->find();
            $group_list=db('auth_rule')->where("module='admin'")->select();
            
            $list=array();
            foreach ($group_list as $v){
                $list[$v['group']][]=$v;
            }
            
            $data=array(
                'list'=>$list,
                'auth_list'=>  explode(',', $group['rules']),
                'id'=>$id
            );
            $this->assign($data);
            return $this->fetch();
        }
    }
    
    //权限列表
    public function access(){
        $list=db('auth_rule')->order('id desc')->paginate(15);
        $data=array(
            'list'=>$list,
            'page'=>$list->render()
        );
        $this->assign($data);
        return $this->fetch();
    }
    
    //添加节点
    public function addNode(){
        if(IS_POST){
            $AuthRule=  model('AuthRule');
            $data=  input('post.');
            $data['module']='admin';
            $is_exists=db('auth_rule')->where("module='admin' and name='".$data['name']."'")->find();
            if($is_exists){
                return $this->error('该节点标识已存在');
            }
            $res=$AuthRule->allowField(true)->save($data);
            if($res){
                return $this->success('添加节点成功',url('access'));
            }else{
                return $this->error($AuthRule->getError());
            }
        }else{
            $data['title']='添加节点';
            $this->assign($data);
            return $this->fetch();
        }
    }
    
    //编辑节点
    public function editNode(){
        $AuthRule=  model('AuthRule');
        $datas=  $this->param;
        if(IS_POST){
            $arr['title']=$datas['title'];
            $arr['name']=$datas['name'];
            $arr['group']=$datas['group'];
            $arr['status']=$datas['status'];
            $arr['type']=$datas['type'];
            $arr['condition']=$datas['condition'];
            $is_exists=db('auth_rule')->where("module='admin' and name='".$datas['name']."' and id!=$datas[id]")->find();
            if($is_exists){
                return $this->error('该节点标识已存在');
            }
            $result=$AuthRule->save($arr,array("id"=>$datas['id']));
            if($result!==false){
                return $this->success('编辑节点成功',url('access'));
            }else{
                return $this->error($AuthRule->getError());
            }
            
        }else{
            $ruleinfo=db('auth_rule')->where("id=$datas[id]")->find();
            $data=array(
                'title'=>'编辑节点',
                'info'=>$ruleinfo
            );
            $this->assign($data);
            return $this->fetch('addNode');
        }
    }
    
    //删除节点
    public function delNode(){
        $data=  $this->param;
        if($data['id']){
            $res=db('auth_rule')->where("id=$data[id]")->delete();
            if($res){
                return $this->success('删除节点成功',url('access'));
            }else{
                return $this->error('删除节点失败');
            }
        }else{
            return $this->error('非法操作');
        }
    }
    
}

