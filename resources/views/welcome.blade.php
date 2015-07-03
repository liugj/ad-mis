@extends('layouts.mis')
@section('title', '首页')
@section('link')
    <link href="/assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
    <link href="/assets/plugins/pintuer/pintuer.css" rel="stylesheet" />
    <link href="/assets/plugins/toastr/toastr.min.css" rel="stylesheet" />
    <link href="/assets/plugins/select2/select2.css" rel="stylesheet" />
    <link href="/assets/plugins/croppic/css/croppic.css" rel="stylesheet" />
    <link href="/assets/plugins/daterangepicker/daterangepicker-bs3.css" rel="stylesheet" />
    <link href="/assets/plugins/weekdaypicker/weekdaypicker.css" rel="stylesheet" />
    <link href="/assets/css/site.css" rel="stylesheet" />
    <link href="/assets/css/plan.css" rel="stylesheet" />
    <link href="/assets/css/index.css" rel="stylesheet" />
    
    <script src="/assets/plugins/jquery-1.11.1.min.js"></script>
    <script src="/assets/plugins/pintuer/pintuer.js"></script>
    <script src="/assets/plugins/toastr/toastr.min.js"></script>
    <!--[if lt IE 9]>
    <script src="/assets/plugins/respond.min.js"></script>
    <script src="/assets/plugins/selectivizr-min.js"></script>
    <![endif]-->
@endsection
@section('sidebar')
@endsection
@section('content')
<div id="banner" class="bg-main doc-intro">
        <div class="container">
            <h1>Ad Mobile</h1>
            <p>国内最优秀的手机广告投放平台</p>
        </div>
</div>
<div id="container">
        <div class="container">
            <div class="main-left">
                <div class="tab">
                    <div class="tab-head">
                        <ul class="tab-nav">
                            <li class="active"><a href="#tabUserLogin">用户登录</a></li>
                            <!--
                            <li class=""><a href="#tabAdminLogin">管理员登录</a></li>
                            -->
                        </ul>
                    </div>
                    <div class="tab-body tab-body-bordered">
                        <div id="tabUserLogin" class="tab-panel active">
                            <form id="formUserLogin" class="form-x" action="/login" method="post" novalidate="novalidate">
                              {!! csrf_field() !!}
                                <div class="form-group">
                                    <div class="label">
                                        <label for="account">账号</label>
                                    </div>
                                    <div class="field">
                                        <input type="text" id="user_account" name="email" class="input" data-val="true" data-val-required="登录账号不能为空">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="label">
                                        <label for="password">密码</label>
                                    </div>
                                    <div class="field">
                                        <input type="password" name="password" class="input" data-val="true" data-val-required="登录密码不能为空">
                                    </div>
                                </div>
                                <!--
                                <div class="form-group">
                                    <div class="label"></div>
                                    <div class="field form-inline">
                                        <input type="text" name="code" class="input input-big" size="10" placeholder="验证码" data-val="true" data-val-required="验证码不能为空">
                                        <img class="img-border" src="code.jpg" width="90" height="45">
                                        &nbsp;<a href="#" class="img-code">换一张</a>
                                    </div>
                                </div>
                                -->
                                <div class="form-group">
                                    <div class="label"></div>
                                    <div class="field">
                                        <label class="i-check">
                                            <input type="checkbox" id="user_rember" name='remember'><i></i>
                                            记住账号
                                        </label>
                                    </div>
                                </div>
                                <div class="form-button">
                                    <div class="alert alert-red" style="display: none;"></div>
                                    <button class="button bg-main" type="submit">
                                        登录
                                        <i class="fa fa-arrow-circle-right"></i>
                                    </button>
                                </div>
                            <div class="form-modal" style="display: none;"><img src="/assets/img/ajax-loading.gif" class="loading"></div></form>
                        </div>
                        <!--
                        <div id="tabAdminLogin" class="tab-panel">
                            <form id="formAdminLogin" class="form-x" action="/admin/login" method="post" novalidate="novalidate">
                                <div class="form-group">
                                    <div class="label">
                                        <label for="account">账号</label>
                                    </div>
                                    <div class="field">
                                        <input type="text" name="username" class="input" data-val="true" data-val-required="登录账号不能为空">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="label">
                                        <label for="password">密码</label>
                                    </div>
                                    <div class="field">
                                        <input type="password" name="password" class="input" data-val="true" data-val-required="登录密码不能为空">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="label"></div>
                                    <div class="field form-inline">
                                        <input type="text" name="code" class="input input-big" size="10" placeholder="验证码" data-val="true" data-val-required="验证码不能为空">
                                        <img class="img-border" src="code.jpg" width="90" height="45">
                                        &nbsp;<a href="#" class="img-code">换一张</a>
                                    </div>
                                </div>
                                <div class="form-button">
                                    <div class="alert alert-red" style="display: none;"></div>
                                    <button class="button bg-main" type="submit">
                                        登录
                                        <i class="fa fa-arrow-circle-right"></i>
                                    </button>
                                </div>
                            <div class="form-modal" style="display: none;"><img src="/assets/img/ajax-loading.gif" class="loading"></div></form>
                        </div>
                                -->
                    </div>
                </div>
                <br>
                <!--
                <div class="browser">
                    <hr>
                    <div id="browserAlert"></div>
                    <h4>请使用以下浏览器登录平台</h4>
                    <p>
                        <a href="http://www.google.cn/intl/zh-CN/chrome/browser/">
                            <img title="Chrome" src="assets/img/chrome.png"> Chrome
                        </a>
                        <a href="http://www.firefox.com.cn/download/">
                            <img title="Firefox" src="assets/img/firefox.png"> Firefox
                        </a>
                        <a href="http://windows.microsoft.com/zh-cn/internet-explorer/ie-11-worldwide-languages/">
                            <img title="IE9+" src="assets/img/ie11.png"> IE8+
                        </a>
                    </p>
                </div>
                -->
            </div>

            <div class="main-right">
                <div class="register line">
                    <h3 class="doc-h3">
                        还没有账号？注册 <i class="fa fa-arrow-circle-right"></i>
                    </h3>
                    <form id="formRegister" class="form-x x12" action="/register" method="post" novalidate="novalidate">
                          {!! csrf_field() !!}
                        <div class="form-group">
                            <div class="label">
                                <label for="username">登录账号</label>
                            </div>
                            <div class="field">
                                <input type="text" class="input" name="name" data-val="true" data-val-required="登录账号不能为空" data-val-regexp="账号格式不正确，只能为大小写英文字母、数字和下划线" data-val-regexp-rule="^\w+$">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="label">
                                <label for="email">邮箱地址</label>
                            </div>
                            <div class="field">
                                <input type="text" class="input" name="email" data-val="true" data-val-required="邮箱地址不能为空" data-val-email="邮箱格式不正确，例：abc@qq.com">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="label">
                                <label for="password">登录密码</label>
                            </div>
                            <div class="field">
                                <input type="password" class="input" name="password" data-val="true" data-val-required="登录密码不能为空" data-val-minlength="密码最少6个字符" data-val-minlength-value="6">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="label">
                                <label for="password_confirm">密码确认</label>
                            </div>
                            <div class="field">
                                <input type="password" class="input" name="password_confirmation" data-val="true" data-val-equalto="两次输入的密码不一致" data-val-equalto-other="password">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="label">
                                <label for="phone">联系电话</label>
                            </div>
                            <div class="field">
                                <input type="text" class="input" name="phone" data-val="true" data-val-required="联系电话不能为空">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="label">
                                <label for="company_name">公司名称</label>
                            </div>
                            <div class="field">
                                <input type="text" class="input" name="company" data-val="true" data-val-required="公司名称不能为空">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="label">
                                <label for="address">公司地址</label>
                            </div>
                            <div class="field">
                                <input type="text" class="input" name="address" data-val="true" data-val-required="公司地址不能为空">
                            </div>
                        </div>
                        <div class="form-button">
                            <div class="alert" style="display: none;"></div>
                            <button class="button bg-yellow" type="submit">
                                立即注册
                                <i class="fa fa-arrow-circle-right"></i>
                            </button>
                        </div>
                    <div class="form-modal" style="display: none;"><img src="/assets/img/ajax-loading.gif" class="loading"></div></form>
                </div>
            </div>
        </div>
    </div>
    <script src="/assets/plugins/jquery.cookie.min.js"></script>
    <script src="/assets/plugins/jquery.form.min.js"></script>
    <script src="/assets/plugins/jquery.validate.min.js"></script>
    <script src="/assets/plugins/juqery.validate.ext.js"></script>
    <script src="/assets/js/app.js"></script>
    <script>
        $(function () {
            var agent = navigator.userAgent;
            if (agent.indexOf('MSIE 6') > 0 || agent.indexOf('MSIE 7') > 0 || agent.indexOf('MSIE 8') > 0) {
                var bAlert = $('<div class="alert alert-red"></div>').html("您的浏览器版本过低，可能无法正常使用系统。");
                $("#browserAlert").append(bAlert);
            }

            var uid = $.cookie("uid");
            if (uid) {
                $("#user_account").val(uid);
                $("#user_rember").prop("checked", true);
            }

            $(".form-button .alert").hide();

            //$(".img-code").click(function (event) {
            //    event.preventDefault();
            //    var image = $(this).prev("img");
            //    var src = image.attr("src").split("?")[0];
            //    image.attr("src", src + "?_sid=" + Math.random());
            //});

            $("#formUserLogin").ajaxFormExt({
                message: false,
                success: function (data) {
                    if (data.success) {
                        if ($("#user_rember").prop("checked")) {
                            $.cookie("uid", uid, { expires: 30, path: "/" });
                        }
                        window.location = "/home";
                    } else {
                        $("#formUserLogin .form-button .alert").html(data.message).show();
                    }
                }
            });

            $("#formAdminLogin").ajaxFormExt({
                message: false,
                success: function (data) {
                    if (data.success) {
                        window.location = "/";
                    } else {
                        $("#formAdminLogin .form-button .alert").html(data.message).show();
                    }
                }
            });

            $("#formRegister").ajaxFormExt({
                message: false,
                success: function (data) {
                    if (data.success) {
                        //$("#formRegister .form-button .alert").removeClass("alert-red").addClass("alert-green").html("<strong>注册成功：</strong>" + data.message).show();
                        window.location = "/home";
                    } else {
                        $("#formRegister .form-button .alert").removeClass("alert-green").addClass("alert-red").html("<strong>注册失败：</strong>" + data.message).show();
                    }
                }
            }); 
        });
    </script>
@endsection
