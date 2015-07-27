@extends('layouts.admin')
@section('title', '客户列表')
@section('link')
    <link href="/assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
    <link href="/assets/plugins/pintuer/pintuer.css" rel="stylesheet" />
    <link href="/assets/plugins/toastr/toastr.min.css" rel="stylesheet" />
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
      var   NavIndex = 1;
      var   Grants   =  ['<?php echo implode("','", App\Role:: grants (Auth :: admin()->get()->role)) ; ?>'];
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
                <h3 class="doc-h3">客户列表</h3>
                <div class="doc-tbar">
                    <div class="button-group">
                        <button class="button active" data-status="0">启用</button>
                        <button class="button" data-status="3">禁用</button>
                        <button class="button" onclick="Admin.User.editUser(0);"><i class="fa fa-plus"></i> 添加</button>
                    </div>
                    <div class="field field-icon-right float-right">
                        <i class="icon icon-search"></i>
                        <input class="input input-auto" size="30" placeholder="输入用户名查询" data-filter="account" />
                    </div>
                    
                </div>
                <table id="gridUser" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>客户名称</th>
                            <th>邮箱</th>
                            <th>电话</th>
                            <th>公司名</th>
                            <th>总额</th>
                            <th>消费</th>
                            <th>成本</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <div id="pageUserBt" class="doc-bbar"></div>
            </div>
        </div>
    </div>
    <script id="tempUserItem" type="text/template">
        <tr>
            <td rowspan="2" class="group">{name}</td>
            <td>{email}</td>
            <td>{basic.phone}</td>
            <td>{basic.company}</td>
            <td>{basic.total}元</td>
            <td>{basic.consume}元</td>
            <td>{basic.cost}元</td>
            <td rowspan="2" class="tool">
                <a href="javascript:;" class="text-main" onclick="Admin.User.editUser({id});">编辑</a>
                <a href="javascript:;" class="text-main" onclick="{tool_action}">{tool_text}</a>
                <br />
                <a href="/admin/user/report/{id}" class="text-main">报表</a>
                {recharge_button}
                <a href="/admin/user/recharge/{id}" class="text-main">充值记录</a> 
            </td>
        </tr>
        <tr>
            <td colspan="4">
                地址：{basic.address}
            </td>
            <td colspan="2">
                添加时间：{created_at}
            </td>
        </tr>
    </script>
   <div id="dialogBalance" class="dialog">
        <div class="dialog-head">
            <span class="close" data-handler="close"></span>
            充值
        </div>
        <div class="dialog-body"></div>
    </div>
    
    <div id="dialogUser" class="dialog">
        <div class="dialog-head">
            <span class="close" data-handler="close"></span>
            客户
        </div>
        <div class="dialog-body"></div>
    </div>

      <script src="/assets/plugins/jquery.form.min.js"></script>

    <script src="/assets/plugins/jquery.validate.min.js"></script>
    <script src="/assets/plugins/juqery.validate.ext.js"></script>
    <script src="/assets/plugins/jquery.pager.js"></script>
    <script src="{{ elixir('assets/js/app.js') }}"></script>
    <script src="{{ elixir('assets/js/admin.user.js') }}"></script>
    <script src="/assets/plugins/jquery-inputmask/jquery.inputmask.js"></script>
    <script src="/assets/plugins/jquery-inputmask/jquery.inputmask.numeric.extensions.js"></script>


@endsection
