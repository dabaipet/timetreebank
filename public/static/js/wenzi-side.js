/* layer*/
layui.use(['layer', 'form'], function(){
    var layer = layui.layer
        ,form = layui.form;
});
//注意：选项卡 依赖 element 模块，否则无法进行功能性操作
layui.use('element', function(){
    var element = layui.element;
    //…
});
/*分类导航*/
$(document).ready(function(){
    $('.sidelist').mousemove(function(){
        $(this).find('.i-list').show();
        $(this).find('.nav0').addClass('hover');
    });
    $('.sidelist').mouseleave(function(){
        $(this).find('.i-list').hide();
        $(this).find('.nav0').removeClass('hover');
    });
});
/*幻灯*/
$(document).ready(function(){
    $('.index-flicker').flicker();
});
$(function(){

    $(".weekly-list li").bind("mouseenter",weekly_ani).bind("mouseleave",function(){
        clearTimeout(
            $(this).data("setTime")
        );
    });

    function weekly_ani(e){
        var me=$(e.target).closest("li");
        if(me.hasClass("current"))
            return;
        var orili=me.parent().find(".current");
        $(this).data("setTime",setTimeout(function(){
            weekly_move(me,orili,100,39)
        },150));
    }

    function weekly_move(me,orili,h,h2){
        me.addClass("current");
        $(".weekly-list li").unbind("mouseenter",weekly_ani);
        setTimeout(function(){
            var cur_h=me.height();
            if(cur_h < h-2){
                var cur_orih=orili.height();
                var dh=Math.round((h-cur_h)/2.5);
                me.css("height",cur_h+dh);
                orili.css("height",cur_orih-dh);
                setTimeout(arguments.callee,25);
            }else{
                me.addClass("current").css("height",h);
                orili.css("height",h2);
                $(".weekly-list li").bind("mouseenter",weekly_ani);
                orili.removeClass("current");
            }
        },25);
    }

    $(".weekly-list").find("li:first").addClass("current").animate({height:100}, 300);

});
