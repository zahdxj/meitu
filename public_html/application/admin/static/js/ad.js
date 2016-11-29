/**
 * 
 * @authors pan
 * @date    2016-11-19 15:48:26
 * ad
 */

$(function(){
	if (page == 'index') {
	    var img_count=parseInt($('input[name="count"]').val());
	    if(img_count>0){
	        for(var i=0;i<img_count;i++){
	            format_image_preview(i);
	        }
	    }else{
	        format_image_preview(0);
	    }
	} else if (page == 'addAd') {
		if ($('input[name=position_id]').val()) {
			var select_position = $("#ad_position").find("option:selected").attr('data-size');
			$('#ad_size_tips').html('<font color="#ff0000">图片尺寸：'+select_position+'（宽*长） 单位：px</font>');
		}
		$("#ad_position").change(function(){
			var select_position = $(this).find("option:selected").attr('data-size');
			var position_id = $(this).find("option:selected").val();
			$('#ad_size_tips').html('图片尺寸：'+select_position+'（宽*长） 单位：px');
			$('input[name=position_id]').val(position_id);
		})
	}
})

function checkForm(){
	if (page == 'addAd') {		//添加广告
	    if(adform.ad_name.value==""){
	        $("#sp1").html("广告名称不能为空");
	        return false;
	    }else{
	        $("#sp1").html("");
	    }
	    if(adform.position_id.value==""){
	        $("#sp2").html("广告位置不能为空");
	        return false;
	    }else{
	        $("#sp2").html("");
	    }
	    return true;
	} else if (page == 'addAdPosition') {		//添加广告位置
        if(adform.position_name.value==""){
            $("#sp1").html("广告位置不能为空");
            return false;
        }else{
            $("#sp1").html("");
        }
        if(adform.ad_width.value==""){
            $("#sp2").html("宽度不能为空");
            return false;
        }else{
            $("#sp2").html("");
        }
        if(adform.ad_height.value==""){
            $("#sp3").html("高度不能为空");
            return false;
        }else{
            $("#sp3").html("");
        }
        return true;
	}
}

// 上传图片并预览
function format_image_preview(id) {
	new uploadPreview({ 
		UpBtn: "up_img_"+id, 
		DivShow: "imgdiv_"+id, 
		ImgShow: "imgShow_"+id	
	});
}
// 添加行
function add_row(){
	var id = $('#tb').find('tr:last').attr('id');
	var nid = parseInt(id.substr(4))+1;
	var html = '<tr id="row_'+nid+'">'+
				'<td><div class="del_btn" onclick="del_row('+nid+')"></div></td>'+
				'<td>'+
					'<div class="goods_img_item" id="goods_img_'+nid+'">'+
		    			'<div id="imgdiv_'+nid+'"><img id="imgShow_'+nid+'" width="320" height="180" onclick="$(\'#up_img_'+nid+'\').click()" src="'+staticPath+'images/plus2.jpg" /></div><input class="img_input" type="file" id="up_img_'+nid+'" name="img_path[]" />'+
		    		'</div>'+
				'</td>'+
				'<td>750*360</td>'+
				'<td><textarea name="img_url[]" rows="3" style="width: 100%"></textarea>'+
				'<td><textarea name="img_desc[]" rows="3" style="width: 100%"></textarea></td>'+
			'</tr>';
	$('#tb').append(html);
	format_image_preview(nid);
	$('#content').scrollTop($('#content')[0].scrollHeight);
}
function del_row(id){
	$('#row_'+id).remove();
}