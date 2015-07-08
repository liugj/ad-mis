@extends('layouts.mis')
@section('title', '广告列表')
@section('link')
    <link href="/assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
    <link href="/assets/plugins/pintuer/pintuer.css" rel="stylesheet" />
    <link href="/assets/plugins/datetimepicker/jquery.datetimepicker.css" rel="stylesheet" />
    <link href="/assets/plugins/select2/select2.css" rel="stylesheet" />
    <link href="/assets/css/site.css" rel="stylesheet" />
    <link href="/assets/css/index.css" rel="stylesheet" />

    <script src="/assets/plugins/jquery-1.11.1.min.js"></script>
    <script src="/assets/plugins/pintuer/pintuer.js"></script>
    <!--[if lt IE 9]>
    <script src="/assets/plugins/respond.min.js"></script>
    <script src="/assets/plugins/selectivizr-min.js"></script>
    <![endif]-->
    <script>
        var NavIndex = 0;
    </script>

@endsection
@section ('content')
<div id="banner" class="bg-main doc-intro">
        <div class="container">
            <h1>Ad Mobile</h1>
            <p>国内最优秀的手机广告投放平台</p>
        </div>
</div>
<div id="container">
        <div class="container">
            <div class="main-left">
                <div id="userIntro">
                    <h3 class="doc-h3">
                        <i class="fa fa-user"></i> 账户信息
                    </h3>
                    <p>
                        用户名：{{$user->name}}<br>
                        余额：{{$basic->total - $basic->consumption_total}}元<br>
                        邮箱：{{$user->email}}<br>
                        电话：{{$basic->phone}}<br>
                        公司：{{$basic->company}}<br>
                        地址：{{$basic->address}}
                    </p>
                </div>
            </div>
            <div class="main-right">
                <div class="panel">
                    <div class="panel-head">
                        <i class="fa fa-signal"></i>
                        账户分日报告
                    </div>
                    <div class="panel-body">      
                        <div class="panel-tbar">
                            <form id="query-form" class="form-inline query">
                               <!--
                                <div class="form-group">
                                    <div class="field">
                                      <select name="consumable_type" style="width:120px;">
                                          <option value="App\Region">地域</option>
                                          <option value="App\Category">兴趣</option>
                                          <option value="App\Industry">行业</option>
                                          <option value="App\Classification">app类型</option>
                                          <option value="App\Operator">运营商</option>
                                          <option value="App\NetWork">网络类型</option>
                                          <option value="App\Device">设备类型</option>
                                          <option value="App\Os">操作系统</option>
                                          <option value="App\Age">年龄</option>
                                      </select>
                                    </div>
                                </div>
                                -->
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
                                  <th>时间</th>
                                  <th></th>
                                  <th>展现次数</th>
                                  <th>点击次数</th>
                                  <th>消费金额(元)</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>

                        <script id="tempDataItem" type="text/template">
                            <tr>
                             <td class="text-center">{datetime}</td>
                             <td class="text-center">{consumable}</td>
                             <td class="text-center">{exhibition_total}</td>
                             <td class="text-right">{click_total}</td>
                             <td class="text-right">{consumption_total}</td>
                            </tr>
                        </script>
                    </div>
                </div>
            </div>
        </div>

        </div>
    </div>
    <script src="/assets/plugins/jquery.validate.min.js"></script>
    <script src="/assets/plugins/juqery.validate.ext.js"></script>
    <script src="/assets/plugins/datetimepicker/jquery.datetimepicker.js"></script>
    <script src="/assets/plugins/select2/select2.min.js"></script>
    <script src="/assets/js/app.js"></script>
    <script src="/assets/js/app.index.js"></script>

@endsection
