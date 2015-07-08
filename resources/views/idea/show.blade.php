<div class="unit-info">
    <blockquote class="height-big">
        <div class="line">
            <div class="x4">
                <label>名称：</label>
                <span>{{$idea->name}}</span>
                <label>费用预算：</label>
                <span>{{$idea->budget}}元/天</span>
                <label>消费：</label><span>{{number_format($idea->consumeTotalByDate(date('Y-m-d')),2)}}元/天</span>
                <label>出价金额：</label>
                <span>{{$idea->bid}}元/点击</span>
                <label>结算方式：</label>
                <span>@if ($idea->pay_type==1) cpm @else cpc @endif  </span>
                <label>创建时间：</label>
                <span>{{$idea->created_at}}</span>
            </div>
            <div class="x8">
                @if($idea->type) 
                <label>类型：</label>
                <span> {{$idea->type}}</span>
                <label>内容：</label>
                @if ($idea->type == 'banner_text') 
                <span>{{$idea->alt}} </span>
                @else 
                <span> <a href="{{$idea->src}}" style="color:#438eb9;" target="_ablank">图片{{$idea->size->width}}x{{$idea->size->height}}</a> @endif</span>
                @endif
                <label>link类型：</label>
                <span>{{$idea->click_action->name}}</span>
                <label>link值：</label>
                <span>{{$idea->link}}</span>
                <label>应用名称：</label>
                <span>{{$idea->link_text}}</span>
                <label>频次控制：</label>
                <span>{{$idea->frequency}}</span>
                <label>更新时间：</label>
                <span>{{$idea->updated_at}}</span>
            </div>
            <hr />
            <div class="x4">
                <?php  $regions = $idea->regions()->get(); ?>
                @if ($regions->count())
                <label>地域：</label>
                <span>@foreach ($regions as $region) {{$region->name}} @endforeach </span>
                @endif
                <?php $operators = $idea->operators()->get() ; ?>
<!--
                <label>性别：</label>
                <span><?php $gender= array('U'=>'不限', 'M'=>'男', 'F'=>'女'); echo isset($gender[$idea->gender]) ? $gender[$idea->gender]: '不限';?></span>
                <?php $industries = $idea->industries()->get(); ?>
                @if ($industries->count())
                <label>行业：</label>
                <span>@foreach($industries as $industry) {{$industry->name}} @endforeach </span>
                @endif 
-->
<!--
                <?php $categories  = $idea->categories()->get(); ?>
                @if ($categories->count())
                <label>兴趣：</label>
                <span> @foreach ($categories as $category) {{$category->name}} @endforeach</span>
                @endif
-->
                <?php  $classification = $idea->classification()->get(); ?>
                @if ($classification->count())
                <label>APP类型：</label>
                <span>@foreach($classification as $classify)  {{$classify->name}} @endforeach</span>
                @endif
                <?php  $bans = $idea->ban()->get(); ?>
                @if ($bans->count())
                <label>APP黑名单：</label>
                <span>@foreach($bans as $classify)  {{$classify->name}} @endforeach</span>
                @endif
                <!--
               <?php $oss = $idea->os()->get(); ?>
               @if ($oss->count())
                <label>操作系统：</label>
                <span>@foreach ($oss as $os) {{$os->name}} @endforeach</span>
               @endif
               -->
<!--
                <?php  $ages = $idea->age()->get(); ?>
                @if ($ages->count())
                <label>年龄段：</label>
                <span> @foreach($ages as $age)  {{$age->name}} @endforeach</span>
                @endif
-->
            </div>
            <div class="x8">
                <label>时间：</label>
                <span><?php $daterange = array(); 
                          if (strtotime($idea->start_time)>0) $daterange[] = date('Y-m-d', strtotime($idea->start_time));
                          if (strtotime($idea->end_time)>0) $daterange[] = date('Y-m-d', strtotime($idea->end_time));
                          if ($daterange) echo implode(' 至 ', $daterange); else echo '不限';
                ?></span>
                <label>时间段：</label>
                <span>
                    <!--值以','符号分隔，表示0-167的可选时间点-->
                    <input type="hidden" id="timerange" value="{{$idea->timerange}}" />
                </span>
                @if ($operators->count())
                <label>运营商：</label>
                <span>@foreach ($operators as $operator) {{$operator->name}} @endforeach</span>
                @endif 
                <?php $networks = $idea->network()->get(); ?>
                @if($networks->count())
                <label>网络类型：</label>
                <span>@foreach($networks as $network) {{$network->name}} @endforeach</span>
               @endif
                <?php $devices = $idea->devices()->get(); ?>
                @if($devices->count())
                <label>网络类型：</label>
                <span>@foreach($devices as $device) {{$device->name}} @endforeach</span>
               @endif
            </div>
        </div>
    </blockquote>
    <div class="margin-top">
        <button class="button" onclick="App.Plan.editUnit({{$idea->id}});">编辑</button>
        @if ($idea->status ==0)
        <button class="button" onclick="App.Plan.deleteUnit({{$idea->id}}, 1);">停止</button>
        @else
        <button class="button" onclick="App.Plan.deleteUnit({{$idea->id}}, 0);">投放</button>
        @endif
    </div>
</div>
