<form id="formBalance" class="form-x" action="/admin/recharge/store" method="post">
    {!! csrf_field() !!}
    <input type="hidden" name="user_id" value="{{$user_id}}" />
    <div class="form-group">
        <div class="label">
            <label for="balance">充值金额</label>
        </div>
        <div class="field">
            <div class="input-group">
                <input type="text" class="input text-right" name="money" value="0.00" data-inputmask="'alias': 'numeric', 'digits': 2" data-val="true" data-val-required="用户余额不能为空" />
                <span class="addon">元</span>
            </div>
        </div>
    </div>
    <div class="form-button">
        <button class="button" type="button" data-handler="close">取消</button>
        <button class="button bg-main" type="submit">保存</button>
    </div>
</form>
