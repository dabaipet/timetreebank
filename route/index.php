<?php
/**
 * API 路由.
 * User:
 * Date: 2018/11/8
 * Time: 17:21
 */
use think\facade\Route;

Route::bind('index');
Route::domain('www', function () {
    //首页
    Route::rule('/', 'index');
    //登录
    Route::rule('signin', 'signin/index','GET|POST');
    Route::rule('signin-choice', 'signin/choice','GET|POST');
    //注册
    Route::rule('signup-user', 'signup/user','GET|POST');
    Route::rule('signup-org', 'signup/org','GET|POST');
    //用户信息
    Route::rule('user', 'user/index','GET|POST');
    Route::rule('user-info', 'user/info','GET|POST');
    Route::rule('user-choice', 'user/choice','GET|POST');
    Route::rule('user-head-pic', 'user/setHeadpic','GET|POST');
    Route::rule('user-real-name', 'user/realName','GET|POST');
    Route::rule('user-choice', 'user/choice','GET|POST');
    Route::rule('user-choice', 'user/choice','GET|POST');
    //文章资讯
    Route::rule('news', 'News/index','GET|POST');
    Route::rule('news/:idd', 'News/read','GET|POST');
    //服务分类
    Route::rule('classify', 'classify/index','GET|POST');
    Route::rule('classify/:cid/:', 'classify/read','GET|POST');
    //Route::get(':c/:a', 'api/:c/:a');
    //Route::rule('worker', 'worker/onMessage','GET|POST');
});