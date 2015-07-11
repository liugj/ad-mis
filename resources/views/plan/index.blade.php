@extends('layouts.mis')
@section('title', '计划')
@section('link')
    <link href="/assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
    <link href="/assets/plugins/pintuer/pintuer.css" rel="stylesheet" />
    <link href="/assets/plugins/toastr/toastr.min.css" rel="stylesheet" />
    <link href="/assets/plugins/select2/select2.css" rel="stylesheet" />
    <link href="/assets/plugins/croppic/css/croppic.css" rel="stylesheet" />
    <link href="/assets/plugins/daterangepicker/daterangepicker-bs3.css" rel="stylesheet" />
    <link href="/assets/plugins/weekdaypicker/weekdaypicker.css" rel="stylesheet" />
    <link href="{{ elixir('assets/css/site.css') }}" rel="stylesheet" />
    <link href="{{ elixir('assets/css/plan.css') }}" rel="stylesheet" />

    <script src="/assets/plugins/jquery-1.11.1.min.js"></script>
    <script src="/assets/plugins/pintuer/pintuer.js"></script>
    <script src="/assets/plugins/toastr/toastr.min.js"></script>
    <!--[if lt IE 9]>
    <script src="/assets/plugins/respond.min.js"></script>
    <script src="/assets/plugins/selectivizr-min.js"></script>
    <![endif]-->
    <script>
        var NavIndex = 1;
    </script>

@endsection
@section ('content')
 <div id="banner" class="bg-main doc-intro">
        <div class="container"></div>
    </div>

    <div id="container">
        <div class="container">
            <div class="main-left">
                <div>
                    <h3 class="doc-h3">
                        <i class="fa fa-photo"></i> 广告计划
                        <button class="button button-small bg-sub" onclick="App.Plan.editPlan(0);"><i class="fa fa-plus"></i> 添加计划</button>
                    </h3>
                    <ul id="listAdv" class="adv-list"></ul>
                </div>                
            </div>
            <div class="main-right">
                <div id="viewInfo">
                    <h3 class="doc-h3">操作说明</h3>
                    <div class="view">
                        <ol class="height-big">
                            <li>
                                <strong>广告计划：</strong>点击左侧[添加计划]按钮，添加新的广告计划；选中广告计划条目后显示详情。
                            </li>
                            <li>
                                <strong>推广单元：</strong>选中左侧广告计划条目后可添加新的推广单元；选中推广单元条目后显示详情。
                            </li>
                        </ol>
                    </div>                    
                </div>
            </div>
        </div>
    </div> 


    <div id="dialogPlan" class="dialog">
        <div class="dialog-head">
            <span class="close" data-handler="close"></span>
            广告计划
        </div>
        <div class="dialog-body"></div>
    </div>

    <div id="dialogUnit" class="dialog">
        <div class="dialog-head">
            <span class="close" data-handler="close"></span>
            广告单元
        </div>
        <div class="dialog-body"></div>
    </div>

    <div id="dialogIdea" class="dialog">
        <div class="dialog-head">
            <span class="close" data-handler="close"></span>
            广告创意
        </div>
        <div class="dialog-body"></div>
    </div>

    <div id="dialogImage" class="dialog">
        <div class="dialog-head">
            <span class="close" data-handler="close"></span>
            图片上传
        </div>
        <div class="dialog-body">
            <div id="croppic"></div>
        </div>
    </div>

    <script src="/assets/plugins/jquery.form.min.js"></script>
    <script src="/assets/plugins/jquery.validate.min.js"></script>
    <script src="/assets/plugins/juqery.validate.ext.js"></script>
    <script src="/assets/plugins/jquery-inputmask/jquery.inputmask.js"></script>
    <script src="/assets/plugins/jquery-inputmask/jquery.inputmask.numeric.extensions.js"></script>
    <script src="/assets/plugins/select2/select2.min.js"></script>
    <script src="/assets/plugins/croppic/croppic.js"></script>
    <script src="/assets/plugins/daterangepicker/moment.min.js"></script>
    <script src="/assets/plugins/daterangepicker/daterangepicker.js"></script>
    <script src="/assets/plugins/weekdaypicker/weekdaypicker.js"></script>
    <script src="{{ elixir('assets/js/app.js') }}"></script>
    <script src="{{ elixir('assets/js/app.plan.js') }}"></script>

@endsection
