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
class Contact extends Base{
    protected $rule = array(
		'contact_name'   => 'require|unique:contact',
        'weixin'=>'require',
	);
	protected $message = array(
		'contact_name.require'    => '客服名称必须',
		'contact_name.unique'     => '客服名称已存在',
		'weixin.require'    => '客服微信号必须',
		'weixin.unique'     => '客服微信号已存在',
	);
	protected $scene = array(
	);

}