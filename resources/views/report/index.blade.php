@extends('layouts.mis')
@section('title', '统计报表')
@section('link')
    <link href="/assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
    <link href="/assets/plugins/pintuer/pintuer.css" rel="stylesheet" />
    <link href="/assets/plugins/toastr/toastr.min.css" rel="stylesheet" />
    <link href="/assets/plugins/datetimepicker/jquery.datetimepicker.css" rel="stylesheet" />
    <link href="/assets/plugins/select2/select2.css" rel="stylesheet" />
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
        var NavIndex = 2;
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
                    </h3>
                    <ul id="listAdv" class="adv-list"></ul>
                </div>
            </div>
            <div class="main-right">
                <div id="viewInfo">
                    <h3 class="doc-h3">操作说明</h3>
                    <div class="view">
                        <ol id="helpReport" class="height-big">
                            <li>
                                点击左侧列表条目显示详情和报表。
                            </li>
                        </ol>
                        <div id="tabReport" class="panel" style="display:none;">
                            <div class="panel-body">
                                <div class="panel-tbar">
                                    <form id="query-form" class="form-inline query">
                                        <div class="form-group">
                                            <div class="field">
                                                <select name="consumable_type" style="width:120px;">
                                                  @foreach (App\ConsumptionDaily :: $Types as $key=>$value)
                                                      <option value="{{$key}}">{{$value}}</option>
                                                  @endforeach
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
                </div>
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

    <script src="/assets/plugins/daterangepicker/moment.min.js"></script>
    <script src="/assets/plugins/datetimepicker/jquery.datetimepicker.js"></script>
    <script src="/assets/plugins/select2/select2.min.js"></script>
    <script src="{{ elixir('assets/js/app.js') }}"></script>
    <script src="{{ elixir('assets/js/app.report.js') }}"></script>
@endsection

