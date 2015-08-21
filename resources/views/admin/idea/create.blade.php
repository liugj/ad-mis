<form id="formIdeaAudit" class="form-x" action="/admin/idea/store" method="post">
    {!! csrf_field() !!}
    <input type="hidden" name="id" value="{{$idea->id}}" />
    @if (in_array($status,array(0,1,2)))
    <div class="form-group">
        <div class="label">
            <label for="status">状态</label>
        </div>
        <div class="field">
             @foreach (App\Idea :: $arrStatus as $key=>$value)
             <?php if (!in_array($key , array(0,2))) continue; ?> 
            <label class="i-radio">
                <input type="radio" name="status" value="{{$key}}" @if ($status ==$key) checked="checked" @endif/><i></i>
            {{$value}}
            </label>
            @endforeach
        </div>
    </div>
    <div class="form-group refuse">
        <div class="label">
            <label for="comment">未通过理由</label>
        </div>
        <div class="field">
        <textarea rows="4" cols="40" name="comment">{{$idea->comment}}</textarea>
        </div>
    </div>
    @endif
    <div class="form-group pass">
        <div class="label">
            <label for="category">分类</label>
        </div>
        <div class="field">
                <select class="select2" id="category_id" style="width:180px;" name="category_id" data-id="{{$idea->category_id}}">
                <option value="0">请选择分类</option>
                @foreach($categories as $category)
                    <option value="{{$category->id}}"  @if ($category->id == $idea->category_id) selected @endif>{{$category->name}}</option>
                @endforeach
                </select>
                <select class="select2" id="category_sub_id" style="width:180px" name="category_sub_id" data-id="{{$idea->category_sub_id}}">
                <option value="0">请选择二级分类</option>
                </select>
                <select class="select2" id="category_grandson_id" style="width:180px" name="category_grandson_id" data-id="{{$idea->category_grandson_id}}">
                <option value="0">请选择三级分类</option>
                </select>
        </div>
    </div>
    <div class="form-group pass append">
        <div class="label">
            <label for="flow">流量分组</label>
        </div>
        <div class="field">
                <select class="select2" style="width:395px" id="flow" name="flow[]" multiple="true">
                @foreach($flows as $flow)
                <option value="{{$flow->id}}">{{$flow->name}} [{{$flow->min}}-{{$flow->max}}]</option>
                @endforeach
                </select>
                <button class="button" type="button" onClick="Admin.Idea.addFlowPrice();">添加</button>
                <button class="button" type="button" onClick="Admin.Idea.addFlow();">添加分组</button>
        </div>
    </div>    
    @foreach($idea->flows as $flow )
    <div class="form-group pass flow price flow{{$flow->id}}">
        <div class="label">
            <label for="flow">{{$flow->name}} [{{$flow->min}} - {{$flow->max}}]的竞价</label>
        </div>
        <div class="input-group">
        <input name="flow_price[{{$flow->id}}][flow_id]" value="{{$flow->id}}" type="hidden" >
        <input type="text" class="input" name="flow_price[{{$flow->id}}][price]" value="{{$flow->pivot->price}}" style="width:395px"><span class="addon">元/天</span>
        <button class="button" type="button" onClick="Admin.Idea.delFlowPrice({{$flow->id}});">删除</button>
        </div>
     </div>   
     @endforeach
    <script id="tempFlowPriceItem" type="text/template">
    <div class="form-group pass flow price flow{id}">
        <div class="label">
            <label for="flow">{flow_name}的竞价</label>
        </div>
        <div class="input-group">
            <input name="flow_price[{id}][flow_id]" value="{id}" type="hidden" >
        <input type="text" class="input" name="flow_price[{id}][price]" value="" style="width:400px"><span class="addon">元/天</span>
        <button class="button" type="button" onClick="Admin.Idea.delFlowPrice({id});">删除</button>
        </div>
     </div>   
    </script>
    <div class="form-button">
        <button class="button" type="button" data-handler="close">取消</button>
        <button class="button bg-main" type="submit">保存</button>
    </div>
</form>
