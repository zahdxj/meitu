<?php

namespace app\common\controller;
use think\Controller;
use think\Db;

class Home extends Controller{
    
    public function _initialize() {
        $this->requestInfo();
        //获取客服列表
        $contact_list=  db('contact')->where("is_show=1")->select();
        $this->assign("contact_list",$contact_list);
        //获取分类
        $cat_list=db('goods_category')->where("level=1 and is_show=1")->select();
        $this->assign("cat_list",$cat_list);
        
        
//        $module=$this->request->module();
//        $controller=$this->request->controller();
//        $action=$this->request->action();
//        if (!isset($_GET['code']) && !session('openid')){
//                $url = createOauthUrlForCode('http://mt.189china.cn/'.$module.'/'.$controller.'/'.$action);
//                Header ( "Location: $url" );
//                exit ();
//        }
//        if(isset($_GET['code']) && !session('openid')){
//            $this->getUserInfo($_GET['code']);
//        }
    }
    

    public function getUserInfo($code){
        $data=  getOpenid($code);
        if(!$data['openid']){
            return $this->error('未获取到openid，请重新进入页面');
        }
        session('openid',$data['openid']);
        $url="https://api.weixin.qq.com/sns/userinfo?access_token=".$data['access_token']."&openid=".$data['openid']."&lang=zh_CN";
        $info=https_request($url);
        $user_info=  json_decode($info,true);
        $is_exists=Db::name('member')->where("openid='".session('openid')."'")->find();
        if(!$is_exists){
            $user['nick_name']=$user_info['nickname'];
            $user['headimgurl']=$user_info['headimgurl'];
            $user['openid']=$user_info['openid'];
            $user['sex'] = $user_info['sex'];
            Db::name('member')->insert($user);
        }
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
