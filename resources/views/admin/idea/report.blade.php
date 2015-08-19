@extends('layouts.admin')
@section('title', '报表')
@section('link')
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
    <link href="/assets/plugins/pintuer/pintuer.css" rel="stylesheet" />
    <link href="/assets/plugins/toastr/toastr.min.css" rel="stylesheet" />
    <link href="/assets/plugins/select2/select2.css" rel="stylesheet" />
    <link href="/assets/plugins/datetimepicker/jquery.datetimepicker.css" rel="stylesheet" />
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
                <div class="account"><span><label>广告名称：</label>{{$idea->name}}</span></div>
                <div class="panel-tbar">
                    <form id="query-form" class="form-inline query" method="get" action="ee">
                         <input type="hidden" name="format" value="json">
                        <div class="form-group">
                            <div class="field">
                                <select name="consumable_type" style="width:120px;">
                                    <option value="App\Region">地域</option>
                                    <option value="App\Classification">app类型</option>
                                    <option value="App\Operator">运营商</option>
                                    <option value="App\NetWork">网络类型</option>
                                    <option value="App\Device">设备类型</option>
                                    <!--
                                    <option value="App\Industry">行业</option>
                                    <option value="App\Os">操作系统</option>
                                    <option value="App\Age">年龄</option>
                                    <option value="App\Category">兴趣</option>
                                    -->
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="field">
                                <div class="input-group">
                                    <input type="text" class="input" size="12" name="date" />
                                    <a class="addon" href="javascript:;" onclick="$(this).prev().datetimepicker('show');"><i class="fa fa-calendar"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                           <button class="button" type="submit"><i class="fa fa-search"></i></button>
                        </div>
                    </form>
                </div>
                                <table id="gridData" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>来源</th>
                                  <th>展现数</th>
                                  <th>点击数</th>
                                  <th>点击率</th>
                                  <th>转化数</th>
                                  <th>转化率</th>
                                            <th>消费金额(元)</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
            </div>
        </div>
    </div>

<script id="tempDataItem" type="text/template">
        <tr>
            <td class="text-center">{consumable}</td>
                             <td class="text-center">{exhibition_total}</td>
                             <td class="text-right">{click_total}</td>
                             <td class="text-right">{click_rate}</td>
                             <td class="text-right">{open_total}</td>
                             <td class="text-right">{convert_rate}</td>
                             <td class="text-right">{consumption_total}</td>
        </tr>
    </script>
    <script src="/assets/plugins/datetimepicker/jquery.datetimepicker.js"></script>
    <script src="/assets/plugins/daterangepicker/moment.min.js"></script>
    <script src="/assets/plugins/select2/select2.min.js"></script>
    <script src="/assets/plugins/jquery.validate.min.js"></script>
    <script src="/assets/plugins/juqery.validate.ext.js"></script>
    <script src="/assets/plugins/jquery.pager.js"></script>
    <script src="{{ elixir('assets/js/app.js') }}"></script>
    <script src="{{ elixir('assets/js/admin.idea.js') }}"></script>
    <script>Admin.Idea.reportList();</script>
@endsection
