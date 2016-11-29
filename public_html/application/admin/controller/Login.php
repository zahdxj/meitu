<?php

/* 
 * 登录
 */
namespace app\admin\controller;
use think\Controller;
use think\Db;

class Login extends Controller{
    
    public function index(){
        return $this->fetch();
    }
    
    public function do_login(){
        $user_name = input( 'post.user_name' ); // 用户名
		$password = input( 'post.password' ); // 密码
		$user_name = isset ( $user_name ) ? trim ( $user_name ) : "";
		$password = isset ( $password ) ? trim ( $password ) : "";
		if (empty ( $user_name ) || empty ( $password )) {
			return $this->error ( "用户名或者密码不能为空" );
		}
        $where['user_name']=$user_name;
        $where['password']=md5($password);
		$res=Db::name("admin_user")->where($where)->find();
        if(!$res){
           return $this->error('用户名或密码不正确');
        }elseif($res['status'] == 0){
           return $this->error('该账号已被禁用');
        }
        session("admin_id",$res['user_id']);
        session("admin_user_name",$res['user_name']);
        $data['last_login_ip']=  get_client_ip();
        $data['last_login_time'] = time();
        $res=Db::name("admin_user")->where("user_id=$res[user_id]")->update($data);
        if($res!==false){
            return $this->success("登录成功",'Index/index');
        }else{
            return $this->error('登录失败');
        }
    }
    
    public function logout(){
        session('admin_id',null);
        session('admin_user_name',null);
        return $this->success('退出成功',url('index'));
    }
    
}
