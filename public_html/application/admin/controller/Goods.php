<?php

/* 
 * 商品管理
 */
namespace app\admin\controller;
use app\common\controller\Common;
use think\Db;

class Goods extends Common{
    
    //商品列表
    public function index(){
        $where=array();
        $data_r=array();
        if(IS_GET){
            if(!empty($_GET['search_id'])){
                $where['goods_id'] = $_GET['search_id'];
                $data_r['goods_id']=$_GET['search_id'];
            }
            if(!empty($_GET['search_name'])){
                $where['goods_name'] = array('like',"%$_GET[search_name]%");
                $data_r['search_name']=$_GET['search_name'];
            }
        }
        $goods_list=db('goods g')->join("h5_goods_category gc","g.cat_id=gc.cat_id")->where($where)->paginate(15);
        $data=array(
            'goods_list'=>$goods_list,
            'page'=>$goods_list->render(),
            'data_r'=>$data_r ? $data_r : array()
        );
        $this->assign($data);
        return $this->fetch();
    }
    
    //添加商品
    public function add(){
        if(IS_POST){
            $data=  $this->param;
            $Goods=model('Goods');
            $data['add_time'] = date("Y-m-d");
            $goods_id=  $Goods->add($data);
            if($goods_id > 0){
                //处理商品图片
                $files=  $this->request->file('goods_img');
                if($files){
                    $img_data=array();
                    foreach($files as $file){
                        // 移动到框架应用根目录/public/uploads/ 目录下
                        $info = $file->move(ROOT_PATH . 'public/uploads/goods/');
                        if($info){
                            // 成功上传后 获取上传信息
                            $file_name=$info->getFilename();
                            $img_data[]=array(
                                'goods_id'=>$goods_id,
                                'img_url'=>'uploads/goods/'.date('Ymd').'/'.$file_name
                            );
                        }else{
                            // 上传失败获取错误信息
                            $this->error($file->getError());
                        }    
                    }
                }
                
                if(!empty($img_data)){
                    db('goods')->where("goods_id=$goods_id")->update(array('goods_thumb'=>$img_data[0]['img_url']));
                    db('goods_gallery')->insertAll($img_data);
                }else{
                    db('goods')->where("goods_id=$goods_id")->update(array('goods_thumb'=>'uploads/no_pic.png'));
                }
                return $this->success('添加商品成功',url('index'));
            }else{
                return $this->error($Goods->getError());
            }
        }else{
            $cat_list=db('goods_category')->select();
            $data=array(
                'cat_list'=>$cat_list,
                'img_count'=>0,
                'title'=>'添加商品',
            );
            $this->assign($data);
            return $this->fetch();
        }
    }

    //编辑商品
    public function edit(){
        $param=  $this->param;
        $goods_id=$param['goods_id'];
        if(IS_POST){
            $result=model('Goods')->edit($param);
            if($result!==false){
                //处理商品图片
                $files=  $this->request->file('goods_img');
                if($files){
                    $img_data=array();
                    foreach($files as $file){
                        // 移动到框架应用根目录/public/uploads/ 目录下
                        $info = $file->move(ROOT_PATH . 'public/uploads/goods/');
                        if($info){
                            // 成功上传后 获取上传信息
                            $file_name=$info->getFilename();
                            $img_data[]=array(
                                'goods_id'=>$goods_id,
                                'img_url'=>'uploads/goods/'.date('Ymd').'/'.$file_name
                            );
                        }else{
                            // 上传失败获取错误信息
                            $this->error($file->getError());
                        }    
                    }
                }
                if(!empty($img_data)){
                    db('goods')->where("goods_id=$goods_id")->update(array('goods_thumb'=>$img_data[0]['img_url']));
                    db('goods_gallery')->where("goods_id=$goods_id")->delete();
                    db('goods_gallery')->insertAll($img_data);
                }else{
                    db('goods')->where("goods_id=$goods_id")->update(array('goods_thumb'=>'uploads/no_pic.png'));
                }
                return $this->success('编辑商品成功',url('index'));
            }else{
                return $this->error(model('Goods')->getError());
            }
            
        }else{
            $goods_info=db('goods')->where("goods_id=$goods_id")->find();
            $goods_info['goods_img']=db('goods_gallery')->where("goods_id=$goods_id")->select();
            $cat_list=db('goods_category')->select();
            $data=array(
                'goods_info'=>$goods_info,
                'cat_list'=>$cat_list,
                'img_count'=>count($goods_info['goods_img']),
                'title'=>'编辑商品'
            );
            $this->assign($data);
            return $this->fetch('add');
        }
    }
    
    //删除商品
    public function del(){
        $goods_id=  $this->param['goods_id'];
        $where['goods_id']=array('in',$goods_id);
        $result = db('goods')->where($where)->delete();
        if($result!==false){
            db('goods_gallery')->where($where)->delete();
            return json_encode(array('error'=>0,'info'=>'删除成功','goods_id'=>  explode(',', $goods_id)));
        }else{
            return json_encode(array('error'=>1,'info'=>'删除失败'));
        }
        
    }

    //商品分类
    public function category(){
        $category_list=db('goods_category')->select();
        $this->assign("list",  unless($category_list));
        return $this->fetch();
    }
    
    //添加商品分类
    public function addCat(){
        if(IS_POST){
            $param=  $this->param;
            $is_exists=db('goods_category')->where("cat_name='".$param['cat_name']."'")->find();
            if($is_exists){
                return $this->error('该分类已存在');
            }
            $data['cat_name']=$param['cat_name'];
            $data['pid']=$param['pid'];
            $data['cat_desc']=$param['cat_desc'];
            $data['is_show']=$param['is_show'];
            if($param['pid']){
                $pid_level=db('goods_category')->where("cat_id=$param[pid]")->field('level')->find();
                $data['level']=$pid_level['level']+1;
            }else{
                $data['level']=1;
            }
            $res=db::name("goods_category")->insert($data);
            if($res){
                return $this->success('添加商品分类成功',url('category'));
            }else{
                return $this->error('添加商品分类失败');
            }
        }else{
            $category_list=db('goods_category')->select();
            $data=array(
                'list'=>unless($category_list),
                'title'=>'添加商品分类'
            );
            $this->assign($data);
            return $this->fetch();
        }
    }


    //编辑商品分类
    public function editCat(){
        $param=  $this->param;
        if(IS_POST){
            $is_exists=db('goods_category')->where("cat_name='".$param['cat_name']."' and cat_id!=$param[cat_id]")->find();
            if($is_exists){
                return $this->error('该分类已存在');
            }
            $data['cat_id']=$param['cat_id'];
            $data['cat_name']=$param['cat_name'];
            $data['pid']=$param['pid'];
            $data['cat_desc']=$param['cat_desc'];
            $data['is_show']=$param['is_show'];
            if($param['pid']){
                $pid_level=db('goods_category')->where("cat_id=$param[pid]")->field('level')->find();
                $data['level']=$pid_level['level']+1;
            }else{
                $data['level']=1;
            }
            $res=db::name("goods_category")->update($data);
            if($res!==false){
                return $this->success('编辑商品分类成功',url('category'));
            }else{
                return $this->error('编辑商品分类失败');
            }
            
        }else{
            $category_list=db('goods_category')->select();
            $info=db('goods_category')->where("cat_id=$param[cat_id]")->find();
            $data=array(
                'list'=>unless($category_list),
                'info'=>$info,
                'title'=>'编辑商品分类'
            );
            $this->assign($data);
            return $this->fetch('addCat');
        }
    }
    
    //删除商品分类
    public function delCat(){
        $cat_id=  $this->param['cat_id'];
        $is_parent=db('goods_category')->where("pid=$cat_id")->select();
        if($is_parent){
            return $this->error('该分类下还有子分类，不能删除');
        }
        $res=db('goods_category')->where("cat_id=$cat_id")->delete();
        if($res!==false){
            return $this->success('删除分类成功');
        }else{
            return $this->error('删除分类失败');
        }
    }
}
