<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
/**
 * 获取客户端IP地址
 * @param integer $type 返回类型 0 返回IP地址 1 返回IPV4地址数字
 * @return mixed
 */
function get_client_ip($type = 0, $adv = false) {
	$type      = $type ? 1 : 0;
	static $ip = NULL;
	if ($ip !== NULL) {
		return $ip[$type];
	}

	if ($adv) {
		if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
			$pos = array_search('unknown', $arr);
			if (false !== $pos) {
				unset($arr[$pos]);
			}

			$ip = trim($arr[0]);
		} elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif (isset($_SERVER['REMOTE_ADDR'])) {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
	} elseif (isset($_SERVER['REMOTE_ADDR'])) {
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	// IP地址合法验证
	$long = sprintf("%u", ip2long($ip));
	$ip   = $long ? array($ip, $long) : array('0.0.0.0', 0);
	return $ip[$type];
}

//检测是否登录
function is_login(){
    if(session('admin_id')){
        return session('admin_id');
    }else{
        return 0;
    }
}

//检测是否是超级管理员
function is_administrator($admin_id=null){
    $admin_id = is_null($admin_id) ? is_login() : $admin_id;
	return $admin_id && (intval($admin_id) === 1);
}

//不区分大小写的in_array
function in_array_case($value,$array){
    return in_array(strtolower($value), array_map('strtolower', $array));
}

function unless($arr,$fid=0){
    static $newarr=array();
    foreach($arr as $v){
        if($v["pid"]==$fid){
            $newarr[]=$v;
            unless($arr,$v["cat_id"]);
        }
    }
    return $newarr;
}

//返回重复一定个数的空格
function returnspace($level){
    return str_repeat("&nbsp;&nbsp;&nbsp;&nbsp;",$level-1)."├─";
}

//获得access_token
function get_access_token(){
	$appid = "wx7f21d8c2b4edd235";
	$appsecret = "6d772bb2256b2f04d62227ff4f576740";
	$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret";
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$output = curl_exec($ch);
	curl_close($ch);
	$jsoninfo = json_decode($output, true);
	$access_token = $jsoninfo["access_token"];
	return $access_token;
}
 
//url请求抓取数据 
function https_request($url, $data = null) {
	$curl = curl_init ();
	curl_setopt ( $curl, CURLOPT_URL, $url );
	curl_setopt ( $curl, CURLOPT_SSL_VERIFYPEER, FALSE );
	curl_setopt ( $curl, CURLOPT_SSL_VERIFYHOST, FALSE );
	if (! empty ( $data )) {
		curl_setopt ( $curl, CURLOPT_POST, 1 );
		curl_setopt ( $curl, CURLOPT_POSTFIELDS, $data );
	}
	curl_setopt ( $curl, CURLOPT_RETURNTRANSFER, 1 );
	$output = curl_exec ( $curl );
	curl_close ( $curl );
	return $output;
}

	
//下载图片（二维码） 
function downloadImage($url){
	$ch = curl_init ($url);
	curl_setopt ( $ch, CURLOPT_HEADER, 0 );
	curl_setopt ( $ch, CURLOPT_NOBODY, 0 );
	curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, FALSE );
	curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, FALSE );
	curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
	
	$package = curl_exec ( $ch );
	$httpinfo = curl_getinfo($ch);
	curl_close ( $ch );
	return array_merge(array('body'=>$package),array('header'=>$httpinfo));
}	



/**
 * 	作用：通过curl向微信提交code，以获取openid
 */
function getOpenid($code)
{
	$url = createOauthUrlForOpenid($code);
	$data = https_request($url);
	$data = json_decode($data,true);
//	$openid = $data['openid'];
	return $data;
}
	
//获得查看用户的openid
function createOauthUrlForCode($redirectUrl)
{
	$urlObj["appid"] = "wx7f21d8c2b4edd235";
	$urlObj["redirect_uri"] = "$redirectUrl";
	$urlObj["response_type"] = "code";
	$urlObj["scope"] = "snsapi_userinfo";
	$urlObj["state"] = "STATE"."#wechat_redirect";
	$bizString = formatBizQueryParaMap($urlObj, true);
	
	return "https://open.weixin.qq.com/connect/oauth2/authorize?".$bizString;
}

/**
 * 	作用：格式化参数，签名过程需要使用
 */
function formatBizQueryParaMap($paraMap, $urlencode)
{

	$buff = "";
	ksort($paraMap);
	foreach ($paraMap as $k => $v)
	{
		if($urlencode)
		{
			$v = urlencode($v);
		}
		$buff .= $k . "=" . $v . "&";
	}
	$reqPar;
	if (strlen($buff) > 0)
	{
		$reqPar = substr($buff, 0, strlen($buff)-1);
	}

	return $reqPar;
}

/**
 * 	作用：生成可以获得openid的url
 */
function createOauthUrlForOpenid($code)
{
	$urlObj["appid"] = "wx7f21d8c2b4edd235";
	$urlObj["secret"] = "6d772bb2256b2f04d62227ff4f576740";
	$urlObj["code"] = $code;
	$urlObj["grant_type"] = "authorization_code";
	$bizString = formatBizQueryParaMap($urlObj, true);
	return "https://api.weixin.qq.com/sns/oauth2/access_token?".$bizString;
}


function Post($curlPost,$url){
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_HEADER, false);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_NOBODY, true);
	curl_setopt($curl, CURLOPT_POST, true);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $curlPost);
	$return_str = curl_exec($curl);
	curl_close($curl);
	return $return_str;
}
function xml_to_array($xml){
	$reg = "/<(\w+)[^>]*>([\\x00-\\xFF]*)<\\/\\1>/";
	if(preg_match_all($reg, $xml, $matches)){
		$count = count($matches[0]);
		for($i = 0; $i < $count; $i++){
		$subxml= $matches[2][$i];
		$key = $matches[1][$i];
			if(preg_match( $reg, $subxml )){
				$arr[$key] = xml_to_array( $subxml );
			}else{
				$arr[$key] = $subxml;
			}
		}
	}
	return $arr;
}