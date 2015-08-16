<?php $role =  Auth ::admin()->get()->role; 
      $roles = array();
      $add = FALSE;
      foreach (App\Role :: $roles as $role_en=>$role_cn) {
          if ($role_en == $role) {
              $add = TRUE ;
          }
          if ($add) $roles[$role_en] = $role_cn;
        }

?>
<form id="formAdmin" class="form-x" action="/admin/administrator/store" method="post">
    {!! csrf_field() !!}
    <input type="hidden" name="id" value="{{$administrator->id}}" />
    <div class="form-group">
        <div class="label">
            <label for="name">姓名</label>
        </div>
        <div class="field">
            <input type="text" name="name" class="input" value="{{$administrator->name}}" data-val="true" data-val-required="姓名不能为空" />
        </div>
    </div>
    <div class="form-group">
        <div class="label">
            <label for="username">角色</label>
        </div>
        <div class="field">
                <select class="select2" id="role" style="width:240px;" name="role">
                @foreach($roles  as $role_en => $role_cn)
                    <option value="{{$role_en}}"  @if ($role_en == $administrator->role) selected @endif>{{$role_cn}}</option>
                @endforeach    
                </select>
        </div>
    </div>
    <div class="form-group">
        <div class="label">
            <label for="email">登录邮箱</label>
        </div>
        <div class="field">
            <input type="text" class="input" name="email"  value="{{$administrator->email}}" data-val="true" data-val-required="邮箱不能为空" data-val-email="邮箱格式不正确，例：abc@qq.com" />
        </div>
    </div>
    @if ($administrator->id <=0)
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
            <label for="status">状态</label>
        </div>
        <div class="field">
            <label class="i-radio">
                <input type="radio" name="status" value="0" @if ($administrator->status ==0) checked="checked" @endif/><i></i>
                启用
            </label>
            <label class="i-radio">
                <input type="radio" name="status" value="3" @if ($administrator->status ==3) checked="checked" @endif /><i></i>
                禁用
            </label>
        </div>
    </div>    
    <div class="form-button">
        <button class="button" type="button" data-handler="close">取消</button>
        <button class="button bg-main" type="submit">保存</button>
    </div>
</form>
