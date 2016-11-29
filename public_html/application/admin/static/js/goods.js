/**
 * 
 * @authors pan
 * @date    2016-11-19 13:37:00
 * goods
 */

$(function(){
	if (page == 'index') {
		//批量删除按钮
		$("#plsc").click(function(){
			var idlist="";
			var idCount=0;
			$(":checkbox").each(function(){
				if ($(this).prop("checked") == true) {
					idlist = idlist+$(this).val()+',';
					idCount++;
				}
			});
			if (idCount==0) {
				alert("请选择要删除的商品！"); 
			} else {
				if (confirm("确定删除这些商品吗?")){
			        var url=$(this).attr('data-url');
					//得到要删除的ID
					$.ajax({
						url:url,
						data:{"goods_id":idlist.substring(0,idlist.length-1)},
						dataType:"json",
						type:"get",
						success:function(data){
			                var datas=eval("("+data+")");
			                if(datas.error==0){
			                    for(var i=0 in datas.goods_id){
			                        $("#remove_"+datas.goods_id[i]).parent().parent().remove();
			                    }
			                }else{
			                    alert(datas.info);
			                } 		
						}
					})
				}
			}
		});
	} else if (page == 'add') {
	    var img_count=parseInt($('input[name="img_count"]').val());
	    if(img_count>0){
	        for(var i=0;i<=img_count;i++){
	            format_image_preview(i);
	        }
	    }else{
	        format_image_preview(0);
	    }
	    //初始化编辑器
		var editor;
		KindEditor.ready(function(K) {
		    editor = K.create('textarea[name="goods_info"]', {
		        cssPath : staticPath+'kindeditor/plugins/code/prettify.css',
		        uploadJson : staticPath+'kindeditor/php/upload_json.php',
		        fileManagerJson : staticPath+'kindeditor/php/file_manager_json.php',
		        allowFileManager : true,
		        filterMode : true
		    });
		});
	}
})

/* index */
//全选
$("#selectAll").click(function(){
	$(":checkbox").attr("checked", true);
 }); 
 // 全不选 
$("#unselect").click(function(){
	$(":checkbox").attr("checked", false);
});
// 反选  
$("#reverse").click(function(){
	$(":checkbox").each(function(){  
		$(this).attr("checked", !$(this).attr("checked"));
	});  
}); 

//删除商品
function goods_remove(url,id){
	if(confirm("确定要删除这条记录吗?")){
			$.post(url,{goods_id:id},function(data){
                var datas=eval("("+data+")");
				if(datas.error==0){
					$("#remove_"+id).parent().parent().remove();
				}else{
					alert(datas.info);
                } 
		},'json');
	}
}

/* add */
// 上传图片并预览
function format_image_preview(id) {
	new uploadPreview({ 
		UpBtn: "up_img_"+id, 
		DivShow: "imgdiv_"+id, 
		ImgShow: "imgShow_"+id,
		callback:function(){
			var nid = id+1;
			if($('#goods_img_'+nid).length==0 && $('#imgShow_'+id).attr('src')!=''+staticPath+'images/plus.jpg'){
				var html = '<div class="col-sm-6 goods_img_item" id="goods_img_'+nid+'">'+
		    			'<div id="imgdiv_'+nid+'"><img id="imgShow_'+nid+'" width="150" height="150" onclick="$(\'#up_img_'+nid+'\').click()" src="'+staticPath+'images/plus.jpg" /></div><input class="img_input" type="file" id="up_img_'+nid+'" name="goods_img[]" />'+
		    		'</div>';
		    	$('#goods_img_box').append(html);
		    	format_image_preview(nid);
		    	var d_html = '<div class="delete_btn" onclick="delete_img('+id+')"></div>';
		    	$('#imgdiv_'+id).append(d_html);
			}
		}	
	});
}
function delete_img(id){
	$('#goods_img_'+id).remove();
}

/* addCat */
function chkname(obj){
	if(obj.value==""){
		$("#sp1").html("分类名称不能为空");
	}else{
        $("#sp1").html("");
    }
}


/* common */
function checkform(){
	if (page == 'add') {
	    if(addform.goods_name.value==""){
	        $("#sp1").html("商品名称不能为空");
	        return false;
	    }else{	
	        $("#sp1").html("");
	    }
	    if(addform.cat_id.value==""){
	        $("#sp2").html("商品分类不能为空");
	        return false;
	    }else{	
	        $("#sp2").html("");
	    }

	    if(addform.goods_number.value==""){
	        $("#sp3").html("商品库存不能为空");
	        return false;
	    }else{
	        $("#sp3").html("");

	    }
	    if(addform.goods_price.value==""){
	        $("#sp4").html("商品价格不能为空");
	        return false;
	    }else{
	        $("#sp4").html("");
	    }
	    return true;
	} else if (page == 'addCat') {
		if(catform.cat_name.value==""){
			$("#sp1").html("分类名称不能为空");
			return false;
		}else{
            $("#sp1").html("");
        }
		return true;
	}
}