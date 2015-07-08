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
                    <a class="logo" title="Ad Mobile" href="/admin/home"> <img src="/assets/img/logo.png" /></a>
                </div>
               @if (Auth::admin()->check())
               @section('sidebar')
               <div class="navbar-body nav-navicon">
                    <ul class="nav nav-menu nav-inline navbar-right">
                        <li>
                            <a href="#">欢迎您，{{{Auth::admin()->get()->name}}} <i class="arrow"></i></a>
                            <ul class="drop-menu pull-right">
                                <li><a href="/admin/logout"><i class="fa fa-power-off"></i> 退出</a></li>
                            </ul>
                        </li>
                    </ul>
               </div>
               @show
              @endif 
            </div>               
        </div>
    </div>
    <!--页头结束-->
       @yield('content')
    <!--页脚-->
    <div id="footer" class="border-main border-top">
        <div class="padding text-center">
            <p class="text-main">版权所有 © shoozen.net All Rights Reserved.</p>
        </div>
    </div>

    
</body>
</html>

