<?php

/* 
 * 权限控制基类
 * 徐晶
 * 2016-11-08
 */
namespace app\common\controller;
use app\common\model\AuthGroup;
use app\common\model\AuthRule;
use think\Controller;

class Common extends Controller{
    public function _initialize() {
        $this->requestInfo();
        //无需认证操作
        define('NOT_AUTH_LIST',config('not_auth_list'));
        define('DENY_AUTH_LIST',config('deny_auth_list'));
        if(!session('admin_id')){
            $this->redirect('admin/login/index');
        }
        define('IS_SUP_ADMIN', is_administrator());
        if(!in_array($this->url, NOT_AUTH_LIST)){
            if(!IS_SUP_ADMIN){
                $access = $this->accessControl();
                if($access === false){
                    $this->error('未授权访问!');
                }elseif($access === null){
                    //检测访问权限
                    if (!$this->checkRule($this->url, array('in', '1,2'))) {
                        $this->error('未授权访问!');
                    }
                }
            }
            
        }
        $this->getMenu();
        $this->assign("controller", strtolower($this->request->controller()));
    }
    
    //获取菜单
    public function getMenu(){
        $where=array();
        $where['module']='admin';
        $where['type']=2;
        $where['status']=1;
        if(!IS_SUP_ADMIN){
            $rules=db('auth_group_access ga')->join("h5_auth_group ag","ga.group_id=ag.id")->where("ga.uid=".session('admin_id'))->field('ag.rules')->find();
            if($rules){
                $where['id']=array('in',  $rules['rules']);
            }
        }
        $menu_list=db('auth_rule')->where($where)->select();
        $data=array();
        foreach ($menu_list as $k=>$v){
            $data[$v['condition']]['title']=$v['group'];
            $data[$v['condition']]['node'][]=$v;
            if(strpos($v['condition'], strtolower($this->request->controller()))!==false){
                $data[$v['condition']]['block']=true;
            }
        }
        $this->assign("menu",$data);
    }


    //权限检测
    public function accessControl(){
        $check = strtolower($this->request->module().'/'.$this->request->controller() . '/' . $this->request->action());
        if(!NOT_AUTH_LIST && in_array_case($check, NOT_AUTH_LIST)){
            //无需认证操作
            return true;
        }
        if(!DENY_AUTH_LIST && in_array_case($check, DENY_AUTH_LIST)){
            //除超管外禁止访问
            return false;
        }
        return null;//需要继续检测权限
    }
    
    final protected function checkRule($rule, $type = AuthRule::rule_url, $mode = 'url') {
		static $Auth = null;
		if (!$Auth) {
		    $config = [
			'AUTH_GROUP' => config('database.prefix') . 'auth_group', // 用户组数据表名
			'AUTH_ACCESS' => config('database.prefix') . 'auth_group_access', // 用户-用户组关系表
			'AUTH_RULE' => config('database.prefix') . 'auth_rule', // 权限规则表
			'AUTH_USER' => config('database.prefix') . 'admin_user', // 用户信息表
			'AUTH_ON' => true, // 认证开关
			'AUTH_TYPE' => 1, // 认证方式，1为实时认证；2为登录认证。
		    ]; 
		    $Auth = new \com\Auth($config);
		}
		if (!$Auth->check($rule, session('admin_id'), $type, $mode)) {
			return false;
		}
		return true;
	}


    //request信息
	protected function requestInfo() {
		$this->param = $this->request->param();
		defined('MODULE_NAME') or define('MODULE_NAME', $this->request->module());
		defined('CONTROLLER_NAME') or define('CONTROLLER_NAME', $this->request->controller());
		defined('ACTION_NAME') or define('ACTION_NAME', $this->request->action());
		defined('IS_POST') or define('IS_POST', $this->request->isPost());
		defined('IS_GET') or define('IS_GET', $this->request->isGet());
		$this->url = strtolower($this->request->module() . '/' . $this->request->controller() . '/' . $this->request->action());
		$this->assign('request', $this->request);
		$this->assign('param', $this->param);
	}
    
}
