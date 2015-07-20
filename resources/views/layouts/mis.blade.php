<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <title>移动互联网广告投放平台-@yield('title')</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @yield('link')
</head>

<body>
    <!--页头-->
    <div id="header">
        <div class="container">
            <div class="navbar padding-big">
                <div class="navbar-head">
                    <button class="button icon-navicon" data-target="#navMain"></button>
                    <a class="logo" title="Ad Mobile" href="/home"> <img src="/assets/img/logo.png" /></a>
                </div>
               @if (Auth::user()->check())
               @section('sidebar')
               <div class="navbar-body nav-navicon" id="navMain">
                    <ul class="nav nav-inline nav-pills border-main nav-big">
                        <li data-index="0"><a href="/home">首页</a></li>
                        <li data-index="1"><a href="/plan">广告管理</a></li>
                        <li data-index="2"><a href="/report">统计报表</a></li>
                    </ul>
                    <ul class="nav nav-menu nav-inline navbar-right">
                        <li>
                            <a href="#">欢迎您，{{{Auth::user()->get()->name}}} <i class="arrow"></i></a>
                            <ul class="drop-menu pull-right">
                                <li><a href="javascript:;" onclick="App.change();"><i class="fa fa-key"></i> 修改信息</a></li>
                                <li><a href="javascript:;" onclick="App.changePwd();"><i class="fa fa-key"></i> 修改密码</a></li>
                                <li class="divider"></li>
                                <li><a href="/logout"><i class="fa fa-power-off"></i> 退出</a></li>
                            </ul>
                        </li>
                    </ul>
               </div>
               @show
              @endif 
            </div>               
        <div id="dialogChangePwd" class="dialog">
            <div class="dialog-head">
                <span class="close" data-handler="close"></span>
                修改密码
            </div>
            <div class="dialog-body"></div>
        </div>
        <div id="dialogChange" class="dialog">
            <div class="dialog-head">
                <span class="close" data-handler="close"></span>
            修改信息
            </div>
            <div class="dialog-body"></div>
        </div>

        </div>
    </div>
    <!--页头结束-->
       @yield('content')
    <!--页脚-->
    <script src="/assets/plugins/jquery.validate.min.js"></script>
    <script src="/assets/plugins/juqery.validate.ext.js"></script>
    <script src="/assets/plugins/jquery.form.min.js"></script>
    <script src="/assets/plugins/jquery-inputmask/jquery.inputmask.js"></script>
    <script src="/assets/plugins/jquery-inputmask/jquery.inputmask.numeric.extensions.js"></script>
    <div id="footer" class="border-main border-top">
        <div class="padding text-center">
            <p class="text-main">版权所有 © shoozen.net All Rights Reserved.</p>
        </div>
    </div>

    
</body>
</html>

