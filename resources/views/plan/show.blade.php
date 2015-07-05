
<blockquote class="height-big">
    <label>计划名称：</label><span>{{$plan->name}}</span>
    <label>预算：</label><span>{{$plan->budget}} 元/天</span>
    <label>消费：</label><span>{{ number_format($plan->consumeTotalByDate(date('Y-m-d')),2)}} 元/天</span>
    <label>创建时间：</label><span>{{$plan->created_at}}</span>
    <label>更新时间：</label><span>{{$plan->updated_at}}</span>
</blockquote>
<div class="margin-top">
    <button class="button" onclick="App.Plan.editPlan({{$plan->id}});">编辑</button>
    @if ($plan->status ==0)
    <button class="button" onclick="App.Plan.deletePlan({{$plan->id}}, 1);">停止</button>
    @else 
    <button class="button" onclick="App.Plan.deletePlan({{$plan->id}}, 0);">启用</button>
    @endif
    <button class="button" onclick="App.Plan.editUnit(0, {{$plan->id}});">添加广告</button>
</div>
