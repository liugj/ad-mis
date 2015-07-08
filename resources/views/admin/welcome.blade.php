@extends('layouts.admin')
@section('title', '管理后台')
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
                            <!--
                            <li class="active"><a href="#tabUserLogin">用户登录</a></li>
                            -->
                            <li class="active"><a href="#tabAdminLogin">管理员登录</a></li>
                        </ul>
                    </div>
                    <div class="tab-body tab-body-bordered">
                        <div id="tabAdminLogin" class="tab-panel active">
                            <form id="formAdminLogin" class="form-x" action="/admin/login" method="post" novalidate="novalidate">
                              {!! csrf_field() !!}
                                <div class="form-group">
                                    <div class="label">
                                        <label for="account">账号</label>
                                    </div>
                                    <div class="field">
                                        <input type="email" name="email" class="input" data-val="true" data-val-required="登录账号不能为空">
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
                                <div class="form-button">
                                    <div class="alert alert-red" style="display: none;"></div>
                                    <button class="button bg-main" type="submit">
                                        登录
                                        <i class="fa fa-arrow-circle-right"></i>
                                    </button>
                                </div>
                            <div class="form-modal" style="display: none;"><img src="/assets/img/ajax-loading.gif" class="loading"></div></form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="main-right">
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
      //      $(".form-button .alert").hide();

            $("#formAdminLogin").ajaxFormExt({
                message: false,
                success: function (data) {
                    if (data.success) {
                        window.location = "/admin/home";
                    } else {
                        $("#formAdminLogin .form-button .alert").html(data.message).show();
                    }
                }
            });

        });
    </script>
@endsection
