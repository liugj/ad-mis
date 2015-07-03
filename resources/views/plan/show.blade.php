
<blockquote class="height-big">
    <label>计划名称：</label><span>{{$plan->name}}</span>
    <label>费用预算：</label><span>{{$plan->budget}}分/天</span>
    <label>创建时间：</label><span>{{$plan->created_at}}</span>
    <label>更新时间：</label><span>{{$plan->updated_at}}</span>
</blockquote>
<div class="margin-top">
    <button class="button" onclick="App.Plan.editPlan({{$plan->id}});">编辑</button>
    <button class="button" onclick="App.Plan.deletePlan({{$plan->id}});">删除</button>
    <button class="button" onclick="App.Plan.editUnit(0, {{$plan->id}});">添加广告</button>
</div>
