<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace app\admin\model;
use app\common\model\Base;

class AdminUser extends Base{
    protected $name='admin_user';
    
    public function register($username, $password){
		$data['user_name'] = $username;
		$data['password'] = $password;
		$result = $this->validate(true)->save($data);
		if ($result) {
			return $this->data['user_id'];
		}else{
			return false;
		}
	}
    
    public function editAdminInfo($data,$ischangepwd = false){
        if ($data['user_id']) {
			if (!$ischangepwd || ($ischangepwd && $data['password'] == '')) {
				unset($data['password']);
			}
			$result = $this->validate('AdminUser.edit')->save($data, array('user_id'=>$data['user_id']));
            if ($result!==false) {
				return $this->save($data, array('user_id'=>$data['user_id']));
			}else{
				return false;
			}
		}else{
			$this->error = "非法操作！";
			return false;
		}
    }

    public function login($username,$password,$type=1){
        $map=array();
        if(\think\Validate::is($username,'email')){
            $type=2;
        }elseif(preg_match("/^1[34578]{1}\d{9}$/",$username)){
            $type=3;
        }
        switch ($type){
            case 1:
                $map['user_name']=$username;
                break;
            case 2:
                $map['email'] = $username;
                break;
            case 3:
                $map['mobile']=$username;
                break;
            default :
                return 0;
        }
        $user = $this->db()->where($map)->find()->toArray();
        if(is_array($user) && $user['status']){
			/* 验证用户密码 */
			if(md5($password) === $user['password']){
				$this->autoLogin($user); //更新用户登录信息
				return $user['user_id']; //登录成功，返回用户ID
			} else {
				return -2; //密码错误
			}
		} else {
			return -1; //用户不存在或被禁用
		}
    }
    
    
    /**
	 * 自动登录用户
	 * @param  integer $user 用户信息数组
	 */
	private function autoLogin($user){
		/* 更新登录信息 */
		$data = array(
			'user_id'             => $user['user_id'],
			'last_login_time' => time(),
			'last_login_ip'   => get_client_ip(),
		);
		$this->where(array('user_id'=>$user['user_id']))->update($data);
		$user = $this->where(array('user_id'=>$user['user_id']))->find();

		session('admin_id', $user['user_id']);
		session('admin_name', $user['user_name']);
	}
    
    public function setPasswordAttr($value){
        return md5($value);
    }


    public function logout(){
		session('admin_id', null);
		session('admin_name', null);
	}
}
