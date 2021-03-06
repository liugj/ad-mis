<div class="unit-info">
    <blockquote class="height-big">
        <div class="line">
            <div class="x4">
                <label>名称：</label>
                <span>{{$idea->name}}</span>
                <label>状态：</label>
                <span> {{$idea->state}}</span>
                <label>费用预算：</label>
                <span>{{$idea->budget}}元/天</span>
                <label>消费：</label><span>{{$idea->consumeTotalByDate(date('Y-m-d'))}}元/天</span>
                @if (isset(App\PayType :: $names[$idea->pay_type]))
                <label>结算方式：</label>
                <span> {{ App\PayType :: $names[$idea->pay_type]}} </span>
                @endif
                <label>出价金额：</label>
                <span>{{$idea->bid}}元</span>
                <label>创建时间：</label>
                <span>{{$idea->created_at}}</span>
            </div>
            <div class="x8">
                <label>投放平台：</label>
                <span> {{App\Idea :: $platforms[$idea->platform]}}</span>
                @if($idea->type) 
                <label>类型：</label>
                <span> {{$idea->type}}</span>
                @endif
                <label>内容：</label>
                @if ($idea->type == 'banner_text') 
                <span>{{$idea->alt}} </span>
                @elseif($idea->group == 'native')
                <span> <a href="{{$idea->src}}" style="color:#438eb9;" target="_ablank">图片{{$idea->size->width}}x{{$idea->size->height}}</a></span>
                @if ($idea->title)
                <label>标题：</label>
                <span>{{$idea->title}}</span>
                @endif
                @if ($idea->description)
                <label>描述：</label>
                <span>{{$idea->description}}</span>
                @endif
                <label>图标：</label>
                <span> <a href="{{$idea->icon}}" style="color:#438eb9;" target="_ablank">图标{{$idea->IconSize->width}}x{{$idea->Iconsize->height}}</a></span>

                @else
                <span> <a href="{{$idea->src}}" style="color:#438eb9;" target="_ablank">图片{{$idea->size->width}}x{{$idea->size->height}}</a></span>
                @endif
                <label>点击类型：</label>
                <span>{{$idea->click_action->name}}</span>
                <label>{{$idea->click_action->placeholder}}：</label>
                <span>{{$idea->link}}</span>
                @if ($idea->click_action->id ==2)
                <label>应用名称：</label>
                <span>{{$idea->link_text}}</span>
                @endif
                @if ($idea->frequency >0)
                <label>频次控制：</label>
                <span>{{$idea->frequency}}</span>
                @endif
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
                <?php  $medias = $idea->medias()->get(); ?>
                @if ($medias->count())
                <label>媒体：</label>
                <span>@foreach ($medias as $media) {{$media->name}} @endforeach </span>
                @endif
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
                <label>应用类型：</label>
                <span>@foreach($classification as $classify)  {{$classify->name}} @endforeach</span>
                @endif
                <?php  $bans = $idea->ban()->get(); ?>
                @if ($bans->count())
                <label>应用黑名单：</label>
                <span>@foreach($bans as $classify)  {{$classify->name}} @endforeach</span>
                @endif
            </div>
            <div class="x8">
               <?php $locations = $idea->location()->get(); ?>
               @if ($locations->count())
                <label>地理位置：</label>
                <span>@foreach ($locations as $location) {{$location->name}} @endforeach</span>
               @endif
<!--
                <?php  $ages = $idea->age()->get(); ?>
                @if ($ages->count())
                <label>年龄段：</label>
                <span> @foreach($ages as $age)  {{$age->name}} @endforeach</span>
                @endif
-->
                <label>时间段：</label>
                <span>
                    <!--值以','符号分隔，表示0-167的可选时间点-->
                    @if ($idea->timerange)
                    <input type="hidden" id="timerange" value="{{$idea->timerange}}" />
                    @else
                      24小时全投
                    @endif
                </span>
                <?php $operators = $idea->operators()->get() ; ?>
                @if ($operators->count())
                <label>运营商：</label>
                <span>@foreach ($operators as $operator) {{$operator->name}} @endforeach</span>
                @endif 
                <?php $networks = $idea->network()->get(); ?>
                @if($networks->count())
                <label>网络类型：</label>
                <span>@foreach($networks as $network) {{$network->name}} @endforeach</span>
               @endif
            </div>
        </div>
    </blockquote>
    <div class="margin-top">
        <button class="button" onclick="App.Plan.editUnit({{$idea->id}});">编辑</button>
        @if ($idea->status ==3)
        <button class="button" onclick="App.Plan.deleteUnit({{$idea->id}}, 4);">停止</button>
        @elseif ($idea->status == 0|| $idea->status==4)
        <button class="button" onclick="App.Plan.deleteUnit({{$idea->id}}, 3);">投放</button>
        @endif
        <button class="button" onclick="App.Plan.preview({{$idea->id}});">预览</button>
    </div>
</div>
