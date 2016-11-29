<?php

/* 
 * 广告管理
 */
namespace app\admin\controller;
use app\common\controller\Common;

class Ad extends Common{
    
    //首页轮播图
    public function index(){
        if(IS_POST){
            $data=  $this->param;
            $files=  $this->request->file('img_path');
            if(!empty($data['id'])){
                foreach ($data['id'] as $k=>$v){
                    $where['id'] =array('not in',$data['id']);
                    $del_id=  db('banner')->where($where)->select();
                    if($del_id){
                        foreach ($del_id as $val){
                            db('banner')->where("id=$val[id]")->delete();
                            @unlink(ROOT_PATH . 'public/'.$val['img_path']);
                        }
                    }
                    
                    $update_data=array('img_url'=>$data['img_url'][$k],'img_desc'=>$data['img_desc'][$k]);
                    if(!empty($_FILES['img_path']['name'][$k])){
                        $banner_info=db('banner')->where("id=$v")->find();
                        if($banner_info){
                            @unlink(ROOT_PATH . 'public/'.$banner_info['img_path']);
                        }
                        $file=$files[$k];
                        // 移动到框架应用根目录/public/uploads/ 目录下
                        $info = $file->move(ROOT_PATH . 'public/uploads/banner/');
                        if($info){
                            // 成功上传后 获取上传信息
                            $file_name=$info->getFilename();
                            $image = \think\Image::open(ROOT_PATH . 'public/uploads/banner/'.date('Ymd').'/'.$file_name);
                            //将图片裁剪为300x300并命名
                            $image->thumb(640, 360,1)->save(ROOT_PATH . 'public/uploads/banner/'.date('Ymd').'/thumb_'.$file_name);
                            @unlink(ROOT_PATH . 'public/uploads/banner/'.date('Ymd').'/'.$file_name);
                            $update_data['img_path']='uploads/banner/'.date('Ymd').'/thumb_'.$file_name;
                        }else{
                            // 上传失败获取错误信息
                            $this->error($file->getError());
                        } 
                    }
                    db('banner')->where("id=$v")->update($update_data);
                    unset($data['img_path'][$k]);
                    unset($data['img_url'][$k]);
                    unset($data['img_desc'][$k]);
                }
            }
            $data_count=!empty($data['id']) ? count($data['id']) : 0;
            $banner=array();
            if(!empty($files)){
                foreach($files as $k=>$file){
                    // 移动到框架应用根目录/public/uploads/ 目录下
                    $info = $file->move(ROOT_PATH . 'public/uploads/banner/');
                    if($info){
                        // 成功上传后 获取上传信息
                        $file_name=$info->getFilename();
                        $image = \think\Image::open(ROOT_PATH . 'public/uploads/banner/'.date('Ymd').'/'.$file_name);
                        //将图片裁剪为300x300并命名
                        $image->thumb(640, 360,1)->save(ROOT_PATH . 'public/uploads/banner/'.date('Ymd').'/thumb_'.$file_name);
                        @unlink(ROOT_PATH . 'public/uploads/banner/'.date('Ymd').'/'.$file_name);
                        $banner[]=array(
                            'img_path'=>'uploads/banner/'.date('Ymd').'/thumb_'.$file_name,
                            'img_url'=>$data['img_url'][$data_count+$k],
                            'img_desc'=>$data['img_desc'][$data_count+$k],
                            'is_show'=>1
                        );
                    }else{
                        // 上传失败获取错误信息
                        $this->error($file->getError());
                    } 
                }
                if(!empty($banner)){
                    db('banner')->insertAll($banner);
                }
            }
            return $this->success('设置成功',url('index'));
            
        }else{
            $banner_list=db('banner')->order('id')->select();
            $data=array(
                'count'=>  count($banner_list),
                'banner_list'=>$banner_list
            );
            $this->assign($data);
            return $this->fetch();
        }
    }
    
    //广告位
    public function adPosition(){
        $ad_position_list=db('ad_position')->order('position_id desc')->paginate(15);
        $data=array(
            'list'=>$ad_position_list,
            'page'=>$ad_position_list->render()
        );
        $this->assign($data);
        return $this->fetch();
    }
    
    //添加广告位
    public function addAdPosition(){
        if(IS_POST){
            $AdPosition=model('AdPosition');
            $data=  $this->param;
            $position_id=$AdPosition->add($data);
            if($position_id >0){
                return $this->success('添加广告位成功',url('adposition'));
            }else{
                return $this->error($AdPosition->getError());
            }
            
        }else{
            $this->assign("title",'添加广告位');
            return $this->fetch();
        }
    }
    
    //编辑广告位
    public function editAdPosition(){
        $datas=  $this->param;
        $position_id=$datas['position_id'];
        if(IS_POST){
            $AdPosition=model('AdPosition');
            $result=$AdPosition->edit($datas);
            if($result!==false){
                $this->success('编辑广告位成功',url('adposition'));
            }else{
                return $this->error($AdPosition->getError());
            }
        }else{
            $position_info=db('ad_position')->where("position_id=$position_id")->find();
            $data=array(
                'info'=>$position_info,
                'title'=>'编辑广告位'
            );
            $this->assign($data);
            return $this->fetch('addAdPosition');
        }
    }
    
    //删除广告位
    public function delAdPosition(){
        $position_id=  $this->param['position_id'];
        $ad_list=db('ad')->where("position_id=$position_id")->select();
        if($ad_list){
            return $this->error('该广告位下有广告，无法删除');
        }
        $res=db('ad_position')->where("position_id=$position_id")->delete();
        if($res!==false){
            return $this->success('广告位删除成功');
        }else{
            return $this->error('广告位删除失败');
        }
    }
    
    //广告列表
    public function adList(){
        $ad_list=db('ad a')->join("h5_ad_position ap","a.position_id=ap.position_id")->field("a.*,ap.position_name")->order('ad_id desc')->paginate(15);
        $data=array(
            'list'=>$ad_list,
            'page'=>$ad_list->render()
        );
        $this->assign($data);
        return $this->fetch();
    }
    
    //添加广告
    public function addAd(){
        if(IS_POST){
            $Ad=model('Ad');
            $data=  $this->param;
            if(strpos($data['ad_link'], 'http://') === false){
                $data['ad_link']='http://'.$data['ad_link'];
            }
            $ad_id=$Ad->add($data);
            if($ad_id >0){
                $file = $this->request->file('ad_img');
                if($file){
                    // 移动到框架应用根目录/public/uploads/ 目录下
                    $info = $file->move(ROOT_PATH . 'public/uploads/ad/');
                    if($info){
                        // 成功上传后 获取上传信息
                        // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
                        $filename=$info->getFilename();
                        db('ad')->where("ad_id=$ad_id")->update(array('ad_img'=>'uploads/ad/'.date('Ymd').'/'.$filename));
                    }else{
                        // 上传失败获取错误信息
                        echo $file->getError();
                    }
                }
                return $this->success('添加广告成功',url('adList'));
            }else{
                return $this->error($Ad->getError());
            }
        }else{
            $ad_position=db('ad_position')->field("position_id,position_name,ad_width,ad_height")->select();
            $data=array(
                'title'=>'添加广告',
                'list'=>$ad_position
            );
            $this->assign($data);
            return $this->fetch();
        }
    }
    
    //编辑广告
    public function editAd(){
        $datas=  $this->param;
        $ad_id=$datas['ad_id'];
        if(IS_POST){
            $Ad=model('Ad');
            if(strpos($datas['ad_link'], 'http://') === false){
                $datas['ad_link']='http://'.$datas['ad_link'];
            }
            $result=$Ad->edit($datas);
            if($result!==false){
                $file = $this->request->file('ad_img');
                if($file){
                    // 移动到框架应用根目录/public/uploads/ 目录下
                    $info = $file->move(ROOT_PATH . 'public/uploads/ad/');
                    if($info){
                        // 成功上传后 获取上传信息
                        // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
                        $filename=$info->getFilename();
                        $origin_file=db('ad')->where("ad_id=$ad_id")->field('ad_img')->find();
                        if($origin_file['ad_img']){
                            @unlink(ROOT_PATH . 'public/'.$origin_file['ad_img']);
                        }
                        db('ad')->where("ad_id=$ad_id")->update(array('ad_img'=>'uploads/ad/'.date('Ymd').'/'.$filename));
                    }else{
                        // 上传失败获取错误信息
                        echo $file->getError();
                    }
                }
                
                $this->success('编辑广告成功',url('adList'));
            }else{
                return $this->error($Ad->getError());
            }
        }else{
            $ad_info=db('ad')->where("ad_id=$ad_id")->find();
            $ad_position=db('ad_position')->field("position_id,position_name,ad_width,ad_height")->select();
            $data=array(
                'info'=>$ad_info,
                'list'=>$ad_position,
                'title'=>'编辑广告'
            );
            $this->assign($data);
            return $this->fetch('addAd');
        }
    }
    
    //删除广告
    public function delAd(){
        $ad_id=  $this->param['ad_id'];
        $res=db('ad')->where("ad_id=$ad_id")->delete();
        if($res!==false){
            return $this->success('广告删除成功');
        }else{
            return $this->error('广告删除失败');
        }
    }
}
