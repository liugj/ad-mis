
<blockquote class="height-big">
    <label>计划名称：</label><span>{{$plan->name}}</span>
    <label>预算：</label><span>{{$plan->budget}} 元/天</span>
    <label>消费：</label><span>{{$plan->consumeTotalByDate(date('Y-m-d'))}} 元/天</span>
    <label>投放日期：</label>
                <span><?php $daterange = array(); 
                          if (strtotime($plan->start_time)>0) $daterange[] = date('Y-m-d', strtotime($plan->start_time));
                          if (strtotime($plan->end_time)>0) $daterange[] = date('Y-m-d', strtotime($plan->end_time));
                          if ($daterange) echo implode(' 至 ', $daterange); else echo '不限';
                ?></span>
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
