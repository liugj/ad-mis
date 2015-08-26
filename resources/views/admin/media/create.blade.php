<form id="formMedia" class="form-x" action="/admin/media/store" method="post">
    {!! csrf_field() !!}
    <input type="hidden" name="id" value="{{$media->id}}" />
    <div class="form-group">
        <div class="label">
            <label for="name">名称</label>
        </div>
        <div class="field">
            <input type="text" name="name" class="input" value="{{$media->name}}" data-val="true" data-val-required="名称不能为空" />
        </div>
    </div>
    <div class="form-group">
        <div class="label">
            <label for="classify">分类</label>
        </div>
        <div class="field">
                    <select id="classify_id" class="select2"  style="width:160px;" name="classify_id" placeholder="请选择一级分类" data-id="{{$media->classify_id}}">
                    <option value="0">请选择一级分类</option>
                    @foreach($classification as $classify)
                    <?php if ($classify->parent !=0) continue;?>
                    <option value="{{$classify->id}}" @if ($classify->id== $media->classify_id) selected @endif>{{$classify->name}}</option>
                    @endforeach
                    </select>
                        <select id="classify_id_1" class="select2"  style="width:160px;"  name="classify_id_1" placeholder="请选择二级分类" data-id="{{$media->classify_id_1}}">
                         <option value="0">请选择二级分类</option>
                        </select>
                        <select id="classify_id_3" class="select2"  style="width:160px;"  name="classify_id_3" placeholder="请选择三级分类" data-id="{{$media->classify_id_3}}">
                    <option value="0">请选择三级分类</option>
                        </select>
        </div>
    </div>
    @foreach($media->devices as $device)
    <hr class="device{{$device->id}}">
    <div class="form-group device{{$device->id}}">
        <div class="label">
            <label for="device[]">设备类型</label>
        </div>
        <div class="field">
            <select class="select2"  style="width:240px;"  name="device_media[{{$device->id}}][device_id]">
            @foreach ($devices as $rawDevice)
                <option  @if($rawDevice->id == $device->id ) selected @endif  value="{{$rawDevice->id}}">{{$rawDevice->name}}</option>
            @endforeach    
            </select>
        </div>
    </div>
    <div class="form-group device{{$device->id}}">
        <div class="label">
            <label for="group[]">广告类型</label>
        </div>
        <div class="field">
            <select class="select2"  style="width:240px;" multiple name="device_media[{{$device->id}}][group][]">
            @foreach ($groups as $rawGroup)
                <option  @if(in_array($rawGroup->name_en, explode(',', $device->pivot->group) )) selected @endif  value="{{$rawGroup->name_en}}">{{$rawGroup->name}}</option>
            @endforeach    
            </select>
        </div>
    </div>
    <div class="form-group device{{$device->id}}">
        <div class="label">
            <label for="appID">APPID</label>
        </div>
        <div class="field">
            <input type="text" class="input" style="width:80%;display:inline-block" name="device_media[{{$device->id}}][adx]"  value="{{$device->pivot->adx}}" data-val="true" data-val-required="APPID不能为空" /> <button class="button" type="button" onClick="Admin.Media.deleteDevice({{$device->id}});">删除</button>
        </div>
    </div>
    @endforeach
    <div class="form-group addDevice">
        <div class="label">    </div>
        <div class="field">
            <label for="add"><button class="button" type="button" onClick="Admin.Media.addDevice();">添加</button></label>
        </div>
   </div>
   <div class="form-group">
      <div class="label">
          <label for="is_star">明星媒体</label>
      </div>
      <div class="field">
          <label class="i-radio">
              <input type="radio" name="is_star" value="Y" @if('Y' == $media->is_star) checked="checked" @endif /><i></i>是
          </label>
      </div>
   </div>
   <div class="form-group">
      <div class="label">
          <label for="status">状态</label>
      </div>
      <div class="field">
          <label class="i-radio">
              <input type="radio" name="status" value="Y" @if('Y' == $media->status) checked="checked" @endif /><i></i>启用
          </label>
          <label class="i-radio">
              <input type="radio" name="status" value="N" @if('N' == $media->status) checked="checked" @endif /><i></i>禁用
          </label>
      </div>
   </div>
   <script id="tempDeviceItem" type="text/template">
    <hr class="device{id}">
    <div class="form-group device{id}">
        <div class="label">
            <label for="device[]">设备类型</label>
        </div>
        <div class="field">
            <select class="select2"  style="width:240px;"  name="device_media[{id}][device_id]">
            @foreach ($devices as $rawDevice)
                <option value="{{$rawDevice->id}}">{{$rawDevice->name}}</option>
            @endforeach    
            </select>
        </div>
    </div>
    <div class="form-group device{id}">
        <div class="label">
            <label for="group[]">广告类型</label>
        </div>
        <div class="field">
            <select class="select2"  style="width:240px;" multiple name="device_media[{id}][group][]">
            @foreach ($groups as $rawGroup)
                <option value="{{$rawGroup->name_en}}">{{$rawGroup->name}}</option>
            @endforeach    
            </select>
        </div>
    </div>
    <div class="form-group device{id}">
        <div class="label">
            <label for="appID">APPID</label>
        </div>
        <div class="field">
            <input type="text" class="input" style="width:80%;display:inline-block" name="device_media[{id}][adx]"  value="" data-val="true" data-val-required="APPID不能为空" /> <button class="button" type="button" onClick="Admin.Media.deleteDevice({id});">删除</button>
        </div>
    </div>
   </script>
    <div class="form-button">
        <button class="button" type="button" data-handler="close">取消</button>
        <button class="button bg-main" type="submit">保存</button>
    </div>
</form>
