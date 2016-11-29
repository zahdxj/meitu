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
class Goods extends Base{
    protected $rule = array(
		'goods_name'   => 'require|unique:goods',
		'cat_id'   => 'require',
		'goods_price'   => 'require',
		'goods_number'   => 'require',
	);
	protected $message = array(
		'goods_name.require'    => '商品名称必须',
		'goods_name.unique'    => '商品名称已存在',
		'cat_id.require' => '商品分类必须',
		'goods_number.require' => '商品库存必须',
		'goods_price.require' => '商品价格必须',
	);
	protected $scene = array(
	);

}