@extends('layouts.admin')
@section('title', '客户列表')
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
      var   NavIndex = 3;
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
                <h3 class="doc-h3">用户列表</h3>
                <div class="doc-tbar">
                    <button class="button" onclick="Admin.Admin.editAdmin(0);"><i class="fa fa-plus"></i> 添加</button>
                    <div class="field field-icon-right float-right">
                        <i class="icon icon-search"></i>
                        <input class="input input-auto" size="30" placeholder="输入姓名查询" data-filter="name" />
                    </div>
                    
                </div>
                <table id="gridAdmin" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>用户名</th>
                            <th>角色</th>
                            <th>登录邮箱</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <div id="pageAdminBt" class="doc-bbar"></div>
            </div>
        </div>
    </div>
    <script id="tempAdminItem" type="text/template">
        <tr>
            <td>{name}</td>
            <td>{role_name}</td>
            <td>{email}</td>
            <td class="tool">
                <a href="javascript:;" class="text-main" onclick="Admin.Admin.editAdmin({id});">编辑</a>
            </td>
        </tr>
    </script>

    <div id="dialogAdmin" class="dialog">
        <div class="dialog-head">
            <span class="close" data-handler="close"></span>
            管理员
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
    <script src="{{ elixir('assets/js/admin.admin.js') }}"></script>
    <script src="/assets/plugins/jquery-inputmask/jquery.inputmask.js"></script>
    <script src="/assets/plugins/jquery-inputmask/jquery.inputmask.numeric.extensions.js"></script>


@endsection
