                <form class="form-x" action="/change" method="post" id="formChange">
    {!! csrf_field() !!}
                    <div class="form-group">
                        <div class="label">
                            <label>用户名</label>
                        </div>
                        <div class="field" style="width:200px;">
                            <input type="text" name="name" class="input"  value="{{$user->name}}" data-val="true" data-val-required="用户名不能为空" />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="label">
                            <label>公司名</label>
                        </div>
                        <div class="field" style="width:200px;">
                            <input type="text" name="company" class="input" value="{{$basic->company}}" data-val="true" data-val-required="公司名不能为空" />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="label">
                            <label>联系电话</label>
                        </div>
                        <div class="field" style="width:200px;">
                            <input type="text" name="phone" class="input" value="{{$basic->phone}}" data-val="true" data-val-required="请输入联系电话" />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="label">
                            <label>公司地址</label>
                        </div>
                        <div class="field" style="width:200px;">
                            <input type="text" name="address" class="input" value="{{$basic->address}}"data-val="true" data-val-required="请输入公司地址" />
                        </div>
                    </div>
                    <div class="form-button">
                        <button class="button" type="button" data-handler="close">取消</button>
                        <button class="button bg-main" type="submit">保存</button>
                    </div>
                </form>

