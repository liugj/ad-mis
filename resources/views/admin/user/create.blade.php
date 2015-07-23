                <form class="form-x" action="/admin/user/store" method="post" id="formUser">
    <input type="hidden" name="id" value="{{$user->id}}" />
    {!! csrf_field() !!}
                    <div class="form-group">
                        <div class="label">
                            <label>用户名</label>
                        </div>
                        <div class="field" >
                            <input type="text" name="name" class="input"  value="{{$user->name}}" data-val="true" data-val-required="用户名不能为空" />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="label">
                            <label for="email">登录邮箱</label>
                        </div>
                        <div class="field">
                            <input type="text" class="input" name="email"  value="{{$user->email}}" data-val="true" data-val-required="邮箱不能为空" data-val-email="邮箱格式不正确，例：abc@qq.com" />
                        </div>
                    </div>
                    @if ($user->id <=0)
                    <div class="form-group">
                        <div class="label">
                            <label for="password">登录密码</label>
                        </div>
                        <div class="field">
                            <!--编辑模式下，后台应输出"******"符号来填充此文本框，提交时判断密码为"******"时，表示不修改密码，正式发布时请删除此注释-->
                            <input type="password" name="password" class="input" value="" data-val="true" data-val-required="登录密码不能为空" />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="label">
                            <label for="password_confirm">密码确认</label>
                        </div>
                        <div class="field">
                            <input type="password" class="input" name="password_confirmation" data-val="true" data-val-equalto="两次输入的密码不一致" data-val-equalto-other="password" />
                        </div>
                    </div>
                    @endif
                    <div class="form-group">
                        <div class="label">
                            <label>公司名</label>
                        </div>
                        <div class="field" >
                            <input type="text" name="company" class="input" value="{{$basic->company}}" data-val="true" data-val-required="公司名不能为空" />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="label">
                            <label>联系电话</label>
                        </div>
                        <div class="field" >
                            <input type="text" name="phone" class="input" value="{{$basic->phone}}" data-val="true" data-val-required="请输入联系电话" />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="label">
                            <label>公司地址</label>
                        </div>
                        <div class="field" >
                            <input type="text" name="address" class="input" value="{{$basic->address}}"data-val="true" data-val-required="请输入公司地址" />
                        </div>
                    </div>
                    <div class="form-button">
                        <button class="button" type="button" data-handler="close">取消</button>
                        <button class="button bg-main" type="submit">保存</button>
                    </div>
                </form>

