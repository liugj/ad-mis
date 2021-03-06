@extends('layouts.admin')
@section('title', '报表')
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
      var  NavIndex = 102;
    </script>
@endsection

@section('content')
    <div id="banner" class="bg-main doc-intro">
        <div class="container"></div>
    </div>
    <div id="container">
        <div class="container">
            <div class="admin-left">
            @include('admin.left_siderbar', ['appends'=>[102=>'报表',]])
            </div>
            <div class="admin-right">
                <h3 class="doc-h3">报表</h3>
                @include('admin.basic',['user'=>$user, 'basic'=>$basic])
                <table id="gridReport" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                           <th>时间</th>
                           <th>展现数</th>
                           <th>点击数</th>
                           <th>点击率</th>
                           <th>下载数</th>
                           <th>安装数</th>
                           <th>转化数</th>
                           <th>转化率</th>
                           <th>消费@if(Auth :: admin()->get()->role == 'admin') /成本 @endif （元）</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <div id="pageReportBt" class="doc-bbar"></div>
            </div>
        </div>
    </div>
    <script id="tempReportItem" type="text/template">
                            <tr>
                             <td class="text-center">{date}</td>
                             <td class="text-center">{exhibition_total}</td>
                             <td class="text-right">{click_total}</td>
                             <td class="text-right">{click_rate}</td>
                             <td class="text-right">{download_total}</td>
                             <td class="text-right">{install_total}</td>
                             <td class="text-right">{open_total}</td>
                             <td class="text-right">{convert_rate}</td>
                             <td class="text-right">{consumption_total}</td>
                            </tr>
    </script>
    <script src="/assets/plugins/jquery.validate.min.js"></script>
    <script src="/assets/plugins/juqery.validate.ext.js"></script>
    <script src="/assets/plugins/jquery.pager.js"></script>
    <script src="{{ elixir('assets/js/app.js') }}"></script>
    <script src="{{ elixir('assets/js/admin.user.js') }}"></script>
    <script> Admin.User.reportList(); </script>
@endsection
