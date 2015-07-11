@extends('layouts.admin')
@section('title', '广告列表')
@section('link')
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
      var  NavIndex = 2;
    </script>
@endsection

@section('content')
    <div id="banner" class="bg-main doc-intro">
        <div class="container"></div>
    </div>
    <div id="container">
        <div class="container">
            <div class="admin-left">
            @include('admin.left_siderbar')
            </div>
            <div class="admin-right">
                <h3 class="doc-h3">创意列表</h3>
                <div class="doc-tbar">
                    <div class="button-group">
                        <button class="button active" data-status="1">待审核</button>
                        <button class="button" data-status="0">审核通过</button>
                        <button class="button" data-status="2">审核未通过</button>
                        <button class="button" data-status="3">投放中</button>
                        <button class="button" data-status="4">暂停投放</button>
                    </div>
                    <div class="field field-icon-right float-right">
                        <i class="icon icon-search"></i>
                        <input class="input input-auto" size="30" placeholder="输入创意名称查询" data-filter="account" />
                    </div>
                    
                </div>
                <table id="gridIdea" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>名称</th>
                            <th>预算</th>
                            <th>出价</th>
                            <th>链接类型</th>
                            <th>所属计划</th>
                            <th>频率控制</th>
                            <th>类型</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <div id="pageIdeaBt" class="doc-bbar"></div>
            </div>
        </div>
    </div>
    <script id="tempIdeaItem" type="text/template">
        <tr>
            <td rowspan="2" class="group">{name}</td>
            <td>{budget}</td>
            <td>{bid}</td>
            <td>{click_action.name}</td>
            <td>{plan.name}</td>
            <td>{frequency}</td>
            <td>{type}</td>
            <td rowspan="2" class="tool">
                <a href="javascript:;" class="text-main" onclick="{tool_action}">{tool_text}</a>
                {operators}
            </td>
        </tr>
        <tr>
            <td colspan="6">
            {content}
            </td>
        </tr>
    </script>
    <script src="/assets/plugins/jquery.validate.min.js"></script>
    <script src="/assets/plugins/juqery.validate.ext.js"></script>
    <script src="/assets/plugins/jquery.pager.js"></script>
    <script src="{{ elixir('assets/js/app.js') }}"></script>
    <script src="{{ elixir('assets/js/admin.idea.js') }}"></script>


@endsection
