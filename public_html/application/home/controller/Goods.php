<?php
namespace app\home\controller;
use app\common\controller\Home;

class Goods extends Home{
    
    //列表页
    public function goods_list(){
        $cat_id = isset($this->param['cat_id']) ? intval($this->param['cat_id']) : 0;
        if($cat_id > 0){
            //取该分类下的商品
            $where['cat_id'] = $cat_id;
        }
        $keywords= isset($this->param['keywords']) ? $this->param['keywords'] : '';
        if($keywords){
            $where['goods_name'] = array('like',"%$keywords%");
        }
        $goods_list = db('goods')->where($where)->order('add_time desc')->paginate(4);
        $this->assign("goods_list",$goods_list);
        $this->assign("page",$goods_list->render());
        
        //广告位
        $ad = db('ad')->where("position_id=7 and is_show=1")->find();
        $this->assign("ad",$ad);
        return $this->fetch();
    }
    
    //商品详情页
    public function goods_info(){
        $goods_id=  $this->param['goods_id'];
        if(!$goods_id){
            return $this->error('非法操作');
        }
        if(session('user_id')){
            $where['user_id'] = session('user_id');
            $where['goods_id'] = $goods_id;
            $is_exists=db('trace')->where($where)->find();
            if(!$is_exists){
                db('trace')->insert($where);
            }
        }
        $goods_info=db('goods')->where("goods_id=$goods_id")->find();
        $this->assign("goods_info",$goods_info);
        $goods_gallery=db('goods_gallery')->where("goods_id=$goods_id")->select();
        $this->assign("goods_gallery",$goods_gallery);
        $ad1=db('ad')->where("position_id=8")->find();
        $this->assign("ad1",$ad1);
        $ad2=db('ad')->where("position_id=9")->find();
        $this->assign("ad2",$ad2);
        //推荐客服
        $contact=db('contact')->order('rand()')->limit(1)->find();
        $this->assign("contact",$contact);
        return $this->fetch();
    }
    
    //footer客服按钮
    public function contact(){
        $ad2=db('ad')->where("position_id=9")->find();
        $this->assign("ad2",$ad2);
        return $this->fetch();
    }
    
    //商品添加收藏
    public function addCollect(){
        if(!session('user_id')){
            return json_encode(array('error'=>2,'info'=>'先登录才能收藏商品哦'));
        }
        $goods_id = $this->param['goods_id'];
        $where['user_id'] = session('user_id');
        $where['goods_id'] = $goods_id;
        $is_collect = db('collect')->where($where)->find();
        if($is_collect){
            return json_encode(array('error'=>1,'info'=>'您已收藏过此商品哦'));
        }else{
            $data['user_id'] = session('user_id');
            $data['goods_id'] = $goods_id;
            $data['add_time'] = time();
            $res=db('collect')->insert($data);
            if($res){
                return json_encode(array('error'=>0,'info'=>'收藏成功'));
            }else{
                return json_encode(array('error'=>1,'info'=>'收藏失败'));
            }
        }
    }
    
    public function login(){
        $module=$this->request->module();
        $controller=$this->request->controller();
        $action=$this->request->action();
        if(isset($this->param['goods_id'])){
            session('goods_id',$this->param['goods_id']);
        }
        if (!isset($_GET['code']) && !session('openid')){
                $url = createOauthUrlForCode('http://mt.189china.cn/'.$module.'/'.$controller.'/'.$action);
                Header ( "Location: $url" );
                exit ();
        }
        if(isset($_GET['code']) && !session('openid')){
            $data=  getOpenid($_GET['code']);
            if(!$data['openid']){
                return $this->error('未获取到openid，请重新进入页面');
            }
            session('openid',$data['openid']);
            //记录会员信息
            $url="https://api.weixin.qq.com/sns/userinfo?access_token=".$data['access_token']."&openid=".$data['openid']."&lang=zh_CN";
            $info=https_request($url);
            $user_info=  json_decode($info,true);
            $member_info=db('member')->where("openid='".session('openid')."'")->find();
            if(!$member_info){
                $user['nick_name']=$user_info['nickname'];
                $user['headimgurl']=$user_info['headimgurl'];
                $user['openid']=$user_info['openid'];
                $user['sex'] = $user_info['sex'];
                $user['add_time'] = time();
                $user_id=db('member')->insertGetId($user);
            }else{
                $user_id=$member_info['user_id'];
            }
            session('user_id',$user_id);
        }
        $this->redirect('goods/goods_info',array('goods_id'=>session('goods_id')));
    }
}

