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
class AdPosition extends Base{
    protected $rule = array(
		'position_name'   => 'require|unique:ad_position',
	);
	protected $message = array(
		'position_name.require'    => '广告位必须',
		'position_name.unique'    => '广告位已存在',
	);
	protected $scene = array(
	);

}