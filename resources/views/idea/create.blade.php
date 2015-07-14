<form id="formUnit" class="form-x" action="/idea/store" method="post">
    {!! csrf_field() !!}
    <div id="setp-1">
        <div class="form-group">
            <div class="label">
                <label for="account">名称</label>
            </div>
            <div class="field" style="width:300px;">
                <input type="text" name="name" class="input" value="{{$idea->name}}" data-val="true" data-val-required="名称不能为空" />
            </div>
        </div>
        <div class="form-group">
            <div class="label">
                <label for="type">类型</label>
            </div>
            <div class="field">
                <select id="type" name="type" style="width:240px;" class="select2">
                   @foreach($groups as $group)
                    <optgroup label="{{$group->name}}">
                        @foreach($group->types()->get() as $type)
                        <option value="{{$type->name_en}}" @if($type->name_en == $idea->type) selected @endif >{{$type->name_zh}}</option>
                        @endforeach
                    </optgroup>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group img">
            <div class="label">
                <label for="size_id">图片尺寸</label>
            </div>
            <div class="field" id="ideaSize">
               @foreach($types as $type)
                <?php if($type->name_en=='banner_text') continue;  ?>
                <div class="{{$type->name_en}}">
                   @foreach($type->sizes()->get() as $size)
                    <label class="i-radio"><input type="radio" name="size_id" value="{{$size->id}}" @if ($idea->size_id == $size->id) checked @endif /><i></i>{{$size->width}}x{{$size->height}}</label><span>{{$size->comment}}</span>
                  @endforeach
                </div>
               @endforeach
            </div>
        </div>
        <div class="form-group img">
            <div class="label">
                <label for="img">图片文件</label>
            </div>
            <div class="field form-inline">
                <input type="text" id="imgUpload" name="src" class="input" readonly="readonly" size="40" value="{{$idea->src}}" data-val="true" data-val-required="图片文件不能为空" />
                <button class="button" type="button" onclick="App.Plan.upload();">上传图片</button>
            </div>
        </div>

        <div class="form-group text">
            <div class="label">
                <label for="text">文字</label>
            </div>
            <div class="field">
                <textarea name="alt" class="input" data-val="true" data-val-required="文字不能为空">{{$idea->alt}}</textarea>
            </div>
        </div>

        <div class="form-group">
            <div class="label">
                <label for="click_action_id">点击类型</label>
            </div>
            <div class="field">
                <select class="select2" id="click_action_id" style="width:240px;" name="click_action_id">
                @foreach($clickActions as $click_action)
                    <option value="{{$click_action->id}}"  @if ($click_action->id == $idea->click_action_id) selected @endif  data-placeholder="{{$click_action->placeholder}}">{{$click_action->name}}</option>
                @endforeach    
                </select>
            </div>
        </div>

        <div class="form-group">
            <div class="label">
                <label for="link" id="linkLabel"></label>
            </div>
            <div class="field">
                <input id="link" name="link" class="input" value="{{$idea->link}}"/>
            </div>
        </div>
        <div class="form-group link_text">
            <div class="label">
                <label for="link_text">应用名称</label>
            </div>
            <div class="field">
                <input id="link_text" name="link_text" class="input" value="{{$idea->link_text}}"/>
            </div>
        </div>

        <div class="form-button">
            <button class="button" type="button" data-handler="close">取消</button>
            <button class="button bg-main" type="button" onclick="App.Plan.gotoSetp(2);">下一步</button>
        </div>
    </div>

    <div id="setp-2" style="display:none;">
        <input type="hidden" name="id" value="{{$idea->id}}" />
        <input type="hidden" name="plan_id" value="{{$idea->id >0 ? $idea->plan->id : $plan_id}}" />
        <div class="line">
            <div class="x6">
                <div class="form-group">
                    <div class="label">
                        <label for="budget">费用预算</label>
                    </div>
                    <div class="field">
                        <div class="input-group">
                            <input type="text" class="input text-right" name="budget" value="{{$idea->budget}}" data-inputmask="'alias': 'numeric', 'digits': 2" data-val="true" data-val-required="费用预算不能为空" />
                            <span class="addon">元/天</span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="label">
                        <label for="frequency">频次控制(每天每个设备展现量)</label>
                    </div>
                    <div class="field">
                    <!--
                        <input type="text" name="frequency" class="input" value="{{$idea->frequency}}" data-val="true" data-val-required="频次控制不能为空" data-val-regexp-rule="^\d+$" />
                        -->
                        <input type="text" name="frequency" class="input" value="{{$idea->frequency}}" placeholder="每天每个设备展现量" />
                    </div>
                </div>
            </div>
            <div class="x6">
                <div class="form-group">
                    <div class="label">
                        <label for="pay_type">结算方式</label>
                    </div>
                    <div class="field">
                    @foreach ($payTypes as $payType=>$payName) 
                        <label class="i-radio">
                            <input type="radio" name="pay_type" value="{{$payType}}" @if($payType == $idea->pay_type) checked="checked" @endif /><i></i>{{$payName}}
                        </label>
                    @endforeach   
                    </div>
                </div>
                <div class="form-group">
                    <div class="label">
                        <label for="bid">出价金额</label>
                    </div>
                    <div class="field">
                        <div class="input-group">
                            <input type="text" class="input text-right" name="bid" value="@if($idea->bid) {{$idea->bid }} @endif" placeholder="点击付费0.2元起投，效果付费2元起投" data-inputmask="'alias': 'numeric', 'digits': 2" data-val="true" data-val-required="出价金额不能为空"/>
                            <span class="addon">元</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr />
        <div class="line">
            <div class="x6">
                <div class="form-group">
                    <div class="label">
                        <label for="region[]">地域</label>
                    </div>
                    <div class="field">
                        <select class="select2"  name="region[]"  style="width:240px;" multiple  placeholder="默认表示全国">
                        @foreach ($regions as $region)
                            <option  @if($idea->regions->contains($region->id) ) selected @endif  value="{{$region->id}}">{{$region->name}}</option>
                        @endforeach    
                        </select>
                    </div>
                </div>
               <!--
                <div class="form-group">
                    <div class="label">
                        <label for="gender">性别</label>
                    </div>
                    <div class="field">
                     <?php foreach (['F'=>'女','M'=>'男', 'U'=>'不限'] as $gender=>$value) : ?>
                        <label class="i-radio">
                            <input type="radio" name="gender" value="{{$gender}}" @if($idea->gender == $gender)  checked="checked" @endif /><i></i>{{$value}}
                        </label>
                    <?php endforeach ?>
                    </div>
                </div>
                <div class="form-group">
                    <div class="label">
                        <label for="industry[]">行业</label>
                    </div>
                    <div class="field">
                        <select class="select2" name="industry[]"  style="width:240px;" multiple >
                        @foreach ($industries as $industry)
                            <option  @if($idea->industries->contains($industry->id) ) selected @endif  value="{{$industry->id}}">{{$industry->name}}</option>
                        @endforeach    
                        </select>
                    </div>
                </div>
                -->
                <!--
                <div class="form-group">
                    <div class="label">
                        <label for="category[]">兴趣</label>
                    </div>
                    <div class="field">
                        <select class="select2"  style="width:240px;" multiple  name="category[]">
                        @foreach ($categories as $category)
                            <option  @if($idea->categories->contains($category->id) ) selected @endif  value="{{$category->id}}">{{$category->name}}</option>
                        @endforeach    
                        </select>
                    </div>
                </div>
                -->
                <div class="form-group">
                    <div class="label">
                        <label for="classify[]">应用类型</label>
                    </div>
                    <div class="field">
                        <select class="select2"  style="width:240px;" multiple  name="classify[]">
                        @foreach ($classification as $classify)
                            <option  @if($idea->classification->contains($classify->id) ) selected @endif  value="{{$classify->id}}">{{$classify->name}}</option>
                        @endforeach    
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <div class="label">
                        <label for="ban[]">应用黑名单</label>
                    </div>
                    <div class="field">
                        <select class="select2"  style="width:240px;" multiple  name="ban[]">
                        @foreach ($classification as $classify)
                            <option  @if($idea->ban->contains($classify->id) ) selected @endif  value="{{$classify->id}}">{{$classify->name}}</option>
                        @endforeach    
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <div class="label">
                        <label for="location[]">地理位置</label>
                    </div>
                    <div class="field">
                        <select class="select2"  style="width:240px;" multiple  name="location[]">
                        @foreach ($locations as $location)
                            <option  @if($idea->location->contains($location->id) ) selected @endif  value="{{$location->id}}">{{$location->name}}</option>
                        @endforeach    
                        </select>
                    </div>
                </div>
                <!--
                <div class="form-group">
                    <div class="label">
                        <label for="age[]">年龄段</label>
                    </div>
                    <div class="field">
                        <select class="select2"  style="width:240px;" multiple  name="age[]">
                        @foreach ($ages as $age)
                            <option  @if($idea->age->contains($age->id) ) selected @endif  value="{{$age->id}}">{{$age->name}}</option>
                        @endforeach    
                        </select>
                    </div>
                </div>
                -->
            </div>
            <div class="x6">
                <div class="form-group">
                    <div class="label">
                        <label for="timerange">时间段</label>
                    </div>
                    <div class="field">
                        <!--值以','符号分隔，表示0-167的可选时间点-->
                        @if($idea->timerange)
                        <input type="hidden" name="timerange" value="{{$idea->timerange}}" />
                        @else
                        默认24小时全投放
                        @endif
                    </div>
                </div>
                <div class="form-group">
                    <div class="label">
                        <label for="operator[]">运营商</label>
                    </div>
                    <div class="field">
                        <select class="select2"  style="width:240px;" multiple  name="operator[]">
                        @foreach ($operators as $operator)
                            <option  @if($idea->operators->contains($operator->id) ) selected @endif  value="{{$operator->id}}">{{$operator->name}}</option>
                        @endforeach    
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <div class="label">
                        <label for="network[]">网络类型</label>
                    </div>
                    <div class="field">
                        <select class="select2"  style="width:240px;" multiple  name="network[]">
                        @foreach ($networks as $network)
                            <option  @if($idea->network->contains($network->id) ) selected @endif  value="{{$network->id}}">{{$network->name}}</option>
                        @endforeach    
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <div class="label">
                        <label for="device[]">设备类型</label>
                    </div>
                    <div class="field">
                        <select class="select2"  style="width:240px;" multiple  name="device[]">
                        @foreach ($devices as $device)
                            <option  @if($idea->devices->contains($device->id) ) selected @endif  value="{{$device->id}}">{{$device->name}}</option>
                        @endforeach    
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-button">
            <button class="button" type="button" data-handler="close">取消</button>
            <button class="button" type="button" onclick="App.Plan.gotoSetp(1);">上一步</button>
            <button class="button bg-main" type="submit">保存</button>
        </div>
    </div>        
</form>
