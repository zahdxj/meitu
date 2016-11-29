$(function(){
    var controller = $('.container').attr('data-controller');
    if ($('.ui_bottom_default').length > 0) {
        $('.ui_bottom_default').find('a').removeClass('weui_bar_item_on');
        $('#nav_'+controller).addClass('weui_bar_item_on');
    }
    //判断是否为商品详情页面
    var page = $('.container').attr('data-page');
    if (page == 'goods_info') {
        $('.ui_bottom_default').remove();
        $('.ui_bottom_primary').removeClass('hide');
    }

    // swiper-slide
    if ($('.swiper-container').length > 0) {
        new Swiper ('.swiper-container', {
            autoplay : 5000,
            speed:300,
            touchRatio : 0.5,
            pagination: '.swiper-pagination'
        })
    }

    // 详情页面TAB
    $('.ui_tab').on('click', '.ui_tab_hd', function () {
        var index = $(this).index();
        $('.ui_tab_hd').removeClass('on');
        $('.ui_tab_bd').addClass('hide');
        $(this).addClass('on');
        $(".ui_tab_bd").eq(index).removeClass('hide');
    })

    //查看详细按钮
    // if ($('.ui_tab_ginfo').height() < 160) {
        $('#btn_unfold').hide();
    // }
    $('.container').one('click', '#btn_unfold', function () {
        $(".ui_tab_ginfo").css('max-height','100%');
        $(this).remove();
    })

    var count = 0;
    /*分类动画效果*/
    $(".ui_animate").css({"height":$(window).height()-$(".ui_fixed").height()-$(".ui_tab").height()+2,"left":-$(".ui_animate").width()})
    $("#show").click(function(){
        if(count == 0){
            $(".ui_animate").animate({left:"0"});
            $(".mask").show();
            count = 1;  
        }else if(count == 1){
            $(".ui_animate").animate({left:-$(".ui_animate").width()});
            $(".mask").hide();
            count = 0;
        }
    })
    $('#catMask').click(function() {
        $(".ui_animate").animate({left:-$(".ui_animate").width()});
        $(".mask").hide();
        count = 0;
    });

    /*加入收藏夹效果*/
    $('body').on('click', '#showToast', function () {
        var goods_id=$("#goods_id").val();
        //判断是否微信登录
        $.post("http://mt.189china.cn/home/goods/addCollect",{"goods_id":goods_id},function(data){
            var data=eval("("+data+")");
            var html = '';
            if(data.error==2){
                html += '<p class="weui_toast_content">请先微信登录才能收藏</p>';
                $('.weui_toast').html(html);
                $('#toast').show();
                setTimeout(function () {
                    location.href = "http://mt.189china.cn/home/goods/login?url=http://mt.189china.cn/home/goods/goods_info/goods_id/"+goods_id;
                }, 2000);
            }else{
                html += '<i class="weui_icon_toast"></i><p class="weui_toast_content">'+data.info+'</p>';
                $('.weui_toast').html(html);
                $('#toast').show();
                setTimeout(function () {
                    $('#toast').hide();
                }, 2000);
            }
        },'json');
    })

    //返回顶部
    goTop($('#gotop'));
})


function JAlert(info){
    var dlg = '';
    dlg += '<div class="weui_dialog_alert" id="dialog" style="display: none;">' +
                '<div class="weui_mask"></div>' +
                '<div class="weui_dialog">' +
                    '<div class="weui_dialog_hd"><strong class="weui_dialog_title">Message</strong></div>' +
                    '<div class="weui_dialog_bd">'+ info +'</div>' +
                    '<div class="weui_dialog_ft">' +
                        '<a href="javascript:$(\'#dialog\').off(\'click\').hide();" class="weui_btn_dialog primary">确定</a>' +
                    '</div>' +
                '</div>' +
            '</div>';
    $('body').append(dlg);
    $('#dialog').show();
}

//返回顶部
function goTop(el){
    var wh = $(window).height();
    var bh = $('body').height();
    var timer = null;
    var scrollTop;
    window.onscroll = function(){
        scrollTop = $('body').scrollTop();
        if(scrollTop > 0){
            el.css('display', 'block');
        }else{
            el.css('display', 'none');
        }
        return scrollTop;
    };
    el.click(function(){
        clearInterval(timer);
        timer = setInterval(function(){
            var speed = (0-scrollTop)/10;
            speed = Math.floor(speed);
            if(scrollTop == 0){
                clearInterval(timer);
            }
            $('body').scrollTop(scrollTop+speed);
        }, 30);
    })
}