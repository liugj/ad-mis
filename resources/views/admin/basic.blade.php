<div class="account">
        <h4>账户信息</h4>
        <div>
            <span><label>用户名：</label>{{$user->name}}</span>
            <span><label>余额：</label>{{$basic->total- $basic->consumeTotal()}}元</span>
            <span><label>登录邮箱：</label>{{$user->email}}</span>
        </div>
        <div>
            <span><label>电话：</label>{{$basic->phone}}</span>
            <span><label>公司：</label>{{$basic->company}}</span>
            <span><label>地址：</label>{{$basic->address}}</span>
        </div>
    </div>
