/**
 * 
 * @authors pan
 * @date    2016-11-19 13:58:32
 * contactList
 */

$(function(){
	if (page == 'contactList') {
		$('.contact-list').find('.thumbnail').each(function(){
			$(this).hover(function() {
	            $(this).children('.ele-mask').show();
			}, function() {
	        	$(this).children('.ele-mask').hide();
			});
		})
	}
})

/* addContact */
function checkForm(){
    if(contactform.contact_name.value==""){
        $("#sp1").html("客服名称不能为空");
        return false;
    }else{
        $("#sp1").html("");
    }
    if(contactform.weixin.value==""){
        $("#sp2").html("客服微信号不能为空");
        return false;
    }else{
        $("#sp2").html("");
    }
    return true;
}