/*
 * 大白宠物医院注册表单JS
 * */
function fleshVerify(){
    $('#imgVerify').attr('src','/captcha?id='+Math.floor(Math.random()*100));
}
//点击图形验证码
$('#code-img').on('click',function(){
    refreshCode();
});
//数据校验
$("#form-login").validate({
    success: function() {
        var formobj = $("#form-login");
        var url = formobj.attr('action');
        proPublic.postAjax(url, formobj.serialize(), function (data) {
            if (data.field) {
                formobj.validate("hideError");
                formobj.validate("showError", data.field, data.msg, Math.random());
            } else if (data.msg) {
                proPublic.alertTips(data.msg, function() {
                    if(data.Turl) {
                        window.location.href=data.Turl;
                    } else{
                        window.location.reload();
                    }
                });
            }
            refreshCode();
        });
        return false;
    }
});
//图形验证码
function refreshCode () {
    if ($('#vcode')) {
        $('#vcode').attr('src', '/captcha?id='+Math.floor(Math.random()*100));
    }
}
function checkLogin(){
    var formdata = new FormData(document.getElementById("signinForm"));
    var furl = $("form").attr("action");

    var mobile = $('#signinMobile').val();
    var password = $('#signinPassword').val();
    var vertify = $('#imgVerifycode').val();
    var signinCode = $('#signinCode').val();
    var myreg = /^(((13[0-9]{1})|(15[0-9]{1})|(18[0-9]{1}))+\d{8})$/;
    if( mobile == ''){
        layer.msg('手机号码不能为空!'); return;
    }
    if( mobile.length!=11 || !myreg.test(mobile)){
        layer.msg('请输入有效的手机号码！');  return;
    }
    if(password =='' || password.length<6){
        layer.msg('请输入有效的登录密码!');return;
    }
    if(vertify =='' || vertify.length!=3){
        layer.msg('请输入正确的图形验证码!');
        $('#imgVerify').trigger('click');return;
    }

    $.ajax({
        url:furl,
        type:'post',
        data:formdata,
        processData:false,
        contentType:false,
        success:function(res){
            if(res.status==1){
                layer.alert(res.msg, {icon: 1,btn: ['确定'],yes: function(index, layero){
                    top.location.href = res.Turl;
                }});
            }else{
                layer.alert(res.msg, {icon: 2});
            }
        },
        error : function(XMLHttpRequest, textStatus, errorThrown) {
            layer.alert('网络失败，请刷新页面后重试', {icon: 2});
        }
    });
}

$(".clickCode").on("click", function() {
    var mobile = $('#signinMobile').val();
    var vertify = $('#imgVerifycode').val();
    var controller = $('#controller').val();
    var s = parseInt($(".clickCode i").html());
    if( mobile == ''){
        layer.msg('请输入有效的手机号！');
        return;
    }
    if(vertify =='' || vertify.length!= 3){
        layer.msg('请输入正确的图形验证码！');
        $('#imgVerify').trigger('click');
        return;
    }
    if (s > 0) {
        return false;
    }
    $.post("http://dabaipet.com/sms/sendSms", {
        regMobile: mobile,
        controller:controller,
        vertify:vertify
    }, function(e) {
        if (e.status == 1) {
            layer.msg(e.msg);
            $(".clickCode").html("<p class=' layui-btn-disabled'><i>60</i>秒后重发</p>");
            var ping = window.setInterval(function() {
                var s = parseInt($(".clickCode i").html());
                if (s == 0) {
                    $(".clickCode").html("重发验证码")
                    clearInterval(ping);
                    return false;
                } else {
                    $(".clickCode i").html(s - 1);
                }
            }, 1000);
        }else if(e.status==2){
            layer.msg(e.msg);
            $('#imgVerify').trigger('click');
        }else {
            layer.msg(e.msg);
        }
    });
    return false;
});
