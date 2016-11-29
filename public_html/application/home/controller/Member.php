<?php

/* 
 * 会员中心
 */
namespace app\home\controller;
use app\common\controller\Home;

class Member extends Home{
    
    //我的收藏
    public function collect(){
       $module=$this->request->module();
       $controller=$this->request->controller();
       $action=$this->request->action();
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
        
        $collect_list=db('collect c')->join("h5_goods g","c.goods_id=g.goods_id")->where("user_id=".session('user_id'))->field('c.collect_id,c.goods_id,g.goods_name,g.goods_thumb')->select();
        $trace_list=db('trace t')->join("h5_goods g","t.goods_id=g.goods_id")->where("user_id=".session('user_id'))->field('t.trace_id,t.goods_id,g.goods_name,g.goods_thumb')->select();
        $data=array(
            'collect_list'=>$collect_list,
            'trace_list'=>$trace_list,
        );
        $this->assign($data);
        return $this->fetch();
    }
    
    //删除我的收藏/足迹
    public function del(){
        $type=  isset($this->param['type']) ? intval($this->param['type']) : 0;
        $goods_id=  isset($this->param['goods_id']) ? intval($this->param['goods_id']) : 0;
        $where['goods_id']=$goods_id;
        $where['user_id']=  session('user_id');
        if($type == 0){
            $res=db('collect')->where($where)->delete();
        }else{
            $res=db('trace')->where($where)->delete();
        }
        if($res){
            return json_encode(array('error'=>0,'info'=>'删除成功'));
        }else{
            return json_encode(array('error'=>1,'info'=>'删除失败'));
        }
    }
    
}
