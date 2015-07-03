@extends('layouts.mis')
@section('title', '广告列表')
@section ('content')
    <div class="main-index">
         <div>
            <h3 class="doc-h3">
               <i class="fa fa-photo"></i>广告管理
               <button class="button button-small bg-sub" onclick="App.Idea.edit(0);"><i class="fa fa-plus"></i>添加广告</button>
            </h3>
            <ul id="listAdv" class="adv-list"></ul>
         </div>
       </div>
    <div>
    <table id="gridAdmin" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>编号</th>
                <th>名称</th>
                <th>预算(分/天)</th>
                <th>竞价</th>
                <th>所属计划</th>
                <th>结算方式</th>
                <th>点击行为</th>
                <th>类型</th>
                <th>状态</th>
                <th>添加时间</th>
                <th>修改时间</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
      @foreach ($ideas as $idea)
          <tr>
            <td>{{ $idea->id }}</td>
            <td>{{ $idea->name }}</td>
            <td>{{ $idea->budget }}</td>
            <td>{{ $idea->bid }}</td>
            <td>{{ $idea->plan->name }}</td>
            <td>@if ($idea->pay_type ==1) cpc @else cpm @endif</td>
            <td>{{ $idea->click_action->name }}</td>
            <td>{{ $idea->type_name->name_zh}}</td>
            <td> @if ($idea->status) 禁用 @else 启用 @endif</td>
            <td>{{ with($idea->created_at)->format('Y-m-d') }}</td>
            <td>{{ with($idea->updated_at)->format('Y-m-d') }}</td>
            <td><a href="/idea/edit/{{$idea->id}}">修改</a> @if ($idea->status==0) 禁用 @else 启用 @endif</td>
         </tr>
      @endforeach
       </tbody>
     </table>
      {!! $ideas->render() !!}
@endsection
