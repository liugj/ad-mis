@extends('layouts.mis')
@section('title', '广告列表')
@section ('content')
<div class="form-group">
        <div class="label">
            <label for="account">用户名</label>
         {{$user->name}}
        </div>
        <div class="label">
            <label for="account">邮箱</label>
         {{$user->email}}
        </div>
        <div class="label">
            <label for="account">余额</label>
         {{$basic->balance}} 分
        </div>
        <div class="label">
            <label for="account">电话</label>
         {{$basic->phone}}
        </div>
        <div class="label">
            <label for="account">公司名称</label>
         {{$basic->company}}
        </div>
        <div class="label">
            <label for="account">地址</label>
         {{$basic->address}}
        </div>
    </div>
@endsection
