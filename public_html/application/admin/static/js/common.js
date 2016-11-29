/*
	common
 */

var page = $('#content').attr('data-page');
var jspath = GetPath ('common');
var staticPath;

$(function(){
	//控制left_main的高度
	var height = 0;
	height = $(window).height();
	$("body").css("height",height);
	$("#content").css("height",height-$("#header").height()-$(".row-fluid").height()+38);
	$(".sidebar-extra").css("height",height-$("#header").height()-$(".visible-phone").height());

	//调用页面js
	var jsname = $('#content').attr('data-controller');
	// $.getScript(jspath + jsname + ".js");
})

//获取js路径
function GetPath (name) {
    var js = document.scripts || document.getElementsByTagName("script");
    var jsPath;
    for (var i = js.length; i > 0; i--) {
        if (js[i - 1].src.indexOf(name + ".js") > -1) {
            jsPath = js[i - 1].src.substring(0, js[i - 1].src.lastIndexOf("/") + 1);
            staticPath = js[i - 1].src.substring(0, js[i - 1].src.lastIndexOf("/") - 2);
        }
    }
    return jsPath;
}

//删除列表元素
function deleteElement(url,id,param){
	if(confirm("确定删除?")){
		location.href = url + '/' + param + '/' + id;	
	}
}