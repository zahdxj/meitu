<?php
namespace app\admin\controller;
use app\common\controller\Common;

class Index extends Common{
    public function index()
    {
        if(session('admin_id') == 1){
            $admininfo=db('admin_user')->where("user_id=".session('admin_id'))->find();
            $admininfo['title'] = '超级管理员';
        }else{
            $admininfo=db('admin_user au')->join("h5_auth_group_access ga","au.user_id=ga.uid")->join("h5_auth_group ag","ga.group_id=ag.id")->field('au.user_name,au.last_login_ip,last_login_time,ag.title')->where("au.user_id=".session("admin_id"))->find();
        }
        $this->assign("admininfo",$admininfo);
        $conf=array();
        $conf["web"]=$_SERVER["SERVER_SOFTWARE"];
        $conf["phpversion"]=phpversion();
        $conf["ip"]=get_client_ip(1);
        $conf["timezone"]=date_default_timezone_get();	
        $conf["code"]="UTF-8";
        $this->assign("conf",$conf);
        return $this->fetch();
    }
}
