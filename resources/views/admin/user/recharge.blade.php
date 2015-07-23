@extends('layouts.admin')
@section('title', '充值记录')
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
      var  NavIndex = 100;
    </script>
@endsection

@section('content')
    <div id="banner" class="bg-main doc-intro">
        <div class="container"></div>
    </div>
    <div id="container">
        <div class="container">
            <div class="admin-left">
            @include('admin.left_siderbar', ['appends'=>[100=>'充值记录']])
            </div>
            <div class="admin-right">
                <h3 class="doc-h3">充值记录</h3>
                    账户信息
                    <p>
                        用户名：{{$user->name}}<br>
                        余额：{{$basic->total-$basic->consumption_total}}元<br>
                        登录邮箱：{{$user->email}}<br>
                        电话：{{$basic->phone}}<br>
                        公司：{{$basic->company}}<br>
                        地址： {{$basic->address}}
                    </p>
                <table id="gridRecharge" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>金额</th>
                            <th>时间</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <div id="pageRechargeBt" class="doc-bbar"></div>
            </div>
        </div>
    </div>
    <script id="tempRechargeItem" type="text/template">
        <tr>
            <td>{money}</td>
            <td>{updated_at}</td>
        </tr>
    </script>
    <script src="/assets/plugins/jquery.validate.min.js"></script>
    <script src="/assets/plugins/juqery.validate.ext.js"></script>
    <script src="/assets/plugins/jquery.pager.js"></script>
    <script src="{{ elixir('assets/js/app.js') }}"></script>
    <script src="{{ elixir('assets/js/admin.user.js') }}"></script>
    <script> Admin.User.rechargeList(); </script>


@endsection
