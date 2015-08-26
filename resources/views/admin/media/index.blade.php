@extends('layouts.admin')
@section('title', '媒体列表')
@section('link')
    <link href="/assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
    <link href="/assets/plugins/pintuer/pintuer.css" rel="stylesheet" />
    <link href="/assets/plugins/toastr/toastr.min.css" rel="stylesheet" />
    <link href="/assets/plugins/select2/select2.css" rel="stylesheet" />
    <link href="{{ elixir('assets/css/site.css') }}" rel="stylesheet" />
    <link href="{{ elixir('assets/css/admin.css') }}" rel="stylesheet" />

    <script src="/assets/plugins/jquery-1.11.1.min.js"></script>
    <script src="/assets/plugins/pintuer/pintuer.js"></script>
    <script src="/assets/plugins/toastr/toastr.min.js"></script>
    <!--[if lt IE 9]>
    <script src="/assets/plugins/respond.min.js"></script>
    <script src="/assets/plugins/selectivizr-min.js"></script>
    <![endif]-->    
    <script>
      var   NavIndex = 4;
    </script>

@endsection
@section('content')
    <div id="banner" class="bg-main doc-intro">
        <div class="container"></div>
    </div>
    <div id="container">
        <div class="container">
           @include('admin.left_siderbar')
            <div class="admin-right">
                <h3 class="doc-h3">媒体列表</h3>
                <div class="doc-tbar">
                    <button class="button" onclick="Admin.Media.editMedia(0);"><i class="fa fa-plus"></i> 添加</button>
                    <div class="field field-icon-right float-right">
                        <i class="icon icon-search"></i>
                        <input class="input input-auto" size="30" placeholder="输入媒体查询" data-filter="media" />
                    </div>
                    
                </div>
                <table id="gridMedia" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>名称</th>
                            <th>一级分类</th>
                            <th>二级分类</th>
                            <th>三级分类</th>
                            <th>设备/广告类型</th>
                            <th>更新时间</th>
                            <th>状态</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <div id="pageMediaBt" class="doc-bbar"></div>
            </div>
        </div>
    </div>
    <script id="tempMediaItem" type="text/template">
        <tr>
            <td>{name}</td>
            <td>{classify.name}</td>
            <td>{classify_1.name}</td>
            <td>{classify_3.name}</td>
            <td>{content}</td>
            <td>{updated_at}</td>
            <td>{statusName}</td>
            <td class="tool">
                <a href="javascript:;" class="text-main" onclick="Admin.Media.editMedia({id});">编辑</a>
            </td>
        </tr>
    </script>

    <div id="dialogMedia" class="dialog">
        <div class="dialog-head">
            <span class="close" data-handler="close"></span>
            媒体
        </div>
        <div class="dialog-body"></div>
    </div>
    <script src="/assets/plugins/select2/select2.min.js"></script>
    <script src="/assets/plugins/select2/select2_locale_zh-CN.js"></script>
    
    <script src="/assets/plugins/jquery.form.min.js"></script>
    <script src="/assets/plugins/jquery.validate.min.js"></script>
    <script src="/assets/plugins/juqery.validate.ext.js"></script>
    <script src="/assets/plugins/jquery.pager.js"></script>
    <script src="{{ elixir('assets/js/app.js') }}"></script>
    <script src="{{ elixir('assets/js/admin.media.js') }}"></script>
    <script src="/assets/plugins/jquery-inputmask/jquery.inputmask.js"></script>
    <script src="/assets/plugins/jquery-inputmask/jquery.inputmask.numeric.extensions.js"></script>
@endsection
