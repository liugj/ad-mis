@extends('layouts.admin')
@section('title', '广告列表')
@section('link')
    <link href="/assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
    <link href="/assets/plugins/pintuer/pintuer.css" rel="stylesheet" />
    <link href="/assets/css/site.css" rel="stylesheet" />
    <link href="/assets/css/admin.css" rel="stylesheet" />

    <script src="/assets/plugins/jquery-1.11.1.min.js"></script>
    <script src="/assets/plugins/pintuer/pintuer.js"></script>
    <!--[if lt IE 9]>
    <script src="/assets/plugins/respond.min.js"></script>
    <script src="/assets/plugins/selectivizr-min.js"></script>
    <![endif]--> 
@endsection
@section('content')
    <div id="banner" class="bg-main doc-intro">
        <div class="container"></div>
    </div>
    <div id="container">
        <div class="container">
           @include('admin.left_siderbar')
            <div class="admin-right">
                <blockquote>
                    <p>用户总数：50</p>
                    <p>今日新增：10</p>
                </blockquote>
            </div>
        </div>
    </div>
    <script src="/assets/plugins/jquery.validate.min.js"></script>
    <script src="/assets/plugins/juqery.validate.ext.js"></script>
    <script src="/assets/js/app.js"></script>
@endsection
