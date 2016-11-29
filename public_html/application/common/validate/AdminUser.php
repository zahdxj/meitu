<?php
// +----------------------------------------------------------------------
// | SentCMS [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.tensent.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: molong <molong@tensent.cn> <http://www.tensent.cn>
// +----------------------------------------------------------------------

namespace app\common\validate;

/**
* 设置模型
*/
class AdminUser extends Base{
    protected $rule = array(
		'user_name'   => 'require|unique:admin_user',
		'password'   => 'require|/^[\w]{5,20}$/',
	);
	protected $message = array(
		'user_name.require'    => '用户名必须',
		'user_name.unique'    => '用户名已存在',
		'password.require' => '密码必须',
	);
	protected $scene = array(
		'edit'  =>'user_name'
	);

}