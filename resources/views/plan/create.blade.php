<form id="formPlan" class="form-x" action="/plan/store" method="post">
    {!! csrf_field() !!}
    <input type="hidden" name="id" value="{{ $plan->id }}" />
    <div class="form-group">
        <div class="label">
            <label for="account">计划名称</label>
        </div>
        <div class="field">
            <input type="text" name="name" class="input" value="{{ $plan->name}}" data-val="true" data-val-required="计划名称不能为空" />
        </div>
    </div>
    <div class="form-group">
        <div class="label">
            <label for="budget">预算</label>
        </div>
        <div class="field">
            <div class="input-group">
                <input type="text" class="input text-right" name="budget" value="{{ $plan->budget}}" data-inputmask="'alias': 'numeric', 'digits': 2" data-val="true" data-val-required="费用预算不能为空" />
                <span class="addon">元/天</span>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="label">
            <label for="daterange">日期</label>
        </div>
        <div class="field">
            <div class="input-group">
                <input type="text" class="input" name="daterange" value="@if (strtotime($plan->start_time)>0) {{substr($plan->start_time,0,10)}} @endif @if (strtotime($plan->end_time)>0) 至 {{substr($plan->end_time,0,10)}}  @endif "/>
                <span class="addon"><i class="fa fa-calendar"></i></span>
            </div>
        </div>
    </div>
    <!-- 
    <div class="form-group">
        <div class="label">
            <label for="status">状态</label>
        </div>
        <div class="field">
            <label class="i-radio">
                <input type="radio" name="status" value="0" @if($plan->status ==0) checked="checked"  @endif />
               <i></i> 启用
            </label>
            <label class="i-radio">
                <input type="radio" name="status" value="1" @if( $plan->status ==1 ) checked="checked" @endif />
                <i></i>禁用
            </label>
        </div>
    </div>
    -->
    <div class="form-button">
        <button class="button bg-main" type="submit">保存</button>
        <button class="button" type="button" data-handler="close">取消</button>
    </div>
</form>

