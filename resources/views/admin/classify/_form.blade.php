{{csrf_field()}}

<div class="layui-form-item">
    <label for="" class="layui-form-label">设备型号</label>
    <div class="layui-input-inline">
        <input type="text" name="category_title" value="{{ $class->category_title ?? old('category_title') }}" lay-verify="required" placeholder="请输入设备型号" class="layui-input" >
    </div>
</div>
<div class="layui-form-item">
    <label for="" class="layui-form-label">排序</label>
    <div class="layui-input-inline">
        <input type="number" name="sort" value="{{ $class->sort ?? 0 }}" lay-verify="required|number" placeholder="请输入数字" class="layui-input" >
        <input type="hidden" name="id" value="{{ $class->id ?? 0 }}"  class="layui-input" >
    </div>
</div>
<!-- <div class="text-center"
             style="position: fixed;bottom: 0;left: 0;right: 0;background-color: #fff;box-shadow: 0 -1px 5px rgba(0,0,0,.15);padding: 15px;">
             <button class="layui-btn layui-btn-primary" type="button" id="closeFormBtn" ew-event="closePageDialog">取消</button>
           
            <button class="layui-btn layui-btn-primary" type="reset">重置表单</button>
            <button class="layui-btn" lay-filter="submitDemo" lay-submit>表单验证</button>
</div> -->


