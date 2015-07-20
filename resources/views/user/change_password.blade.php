                <form class="form-x" action="/change_password" method="post" id="formChangePwd">
    {!! csrf_field() !!}
                    <div class="form-group">
                        <div class="label">
                            <label>原密码</label>
                        </div>
                        <div class="field" style="width:200px;">
                            <input type="password" name="oldPassword" class="input" data-val="true" data-val-required="原密码不能为空" />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="label">
                            <label>新密码</label>
                        </div>
                        <div class="field" style="width:200px;">
                            <input type="password" name="password" class="input" data-val="true" data-val-required="新密码不能为空" />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="label">
                            <label>密码确认</label>
                        </div>
                        <div class="field" style="width:200px;">
                            <input type="password" name="password_confirmation" class="input" data-val="true" data-val-required="请再次输入新密码"
                                   data-val-equalto="两次输入的密码不一致" data-val-equalto-other="password" />
                        </div>
                    </div>
                    <div class="form-button">
                        <button class="button" type="button" data-handler="close">取消</button>
                        <button class="button bg-main" type="submit">保存</button>
                    </div>
                </form>

