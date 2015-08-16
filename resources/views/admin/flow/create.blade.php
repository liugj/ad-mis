<form class="form-x" action="/admin/flow/store" method="post" id="formFlow">
    {!! csrf_field() !!}
   <div class="form-group">
       <div class="label">
           <label>组名</label>
       </div>
       <div class="field" >
           <input type="text" name="name" class="input"  value="" data-val="true" data-val-required="组名不能为空" />
       </div>
   </div>
   <div class="form-group">
       <div class="label">
           <label for="min">最小值</label>
       </div>
       <div class="field">
           <input type="text" class="input" name="min"  value="" data-val="true" data-val-required="最小in"/>
       </div>
   </div>
   <div class="form-group">
       <div class="label">
           <label for="max">最大值</label>
       </div>
       <div class="field">
           <input type="text" class="input" name="max"  value="" data-val="true" data-val-required="最大值"/>
       </div>
   </div>

   <div class="form-button">
       <button class="button" type="button" data-handler="close">取消</button>
       <button class="button bg-main" type="submit">保存</button>
   </div>
 </form>

