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
class Ad extends Base{
    protected $rule = array(
		'ad_name'   => 'require|unique:ad',
        'position_id'=>'require'
	);
	protected $message = array(
		'ad_name.require'    => '广告名称必须',
		'ad_name.unique'     => '广告名称已存在',
        'position_id'        => '广告位必须'
	);
	protected $scene = array(
	);

}