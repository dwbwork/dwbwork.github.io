{{csrf_field()}}

<div class="layui-form-item">
    <label for="" class="layui-form-label">设备名称</label>
    <div class="layui-input-inline">
        <input type="text" name="plant_title" value="{{ $plants->plant_title ?? old('plant_title') }}" lay-verify="required" placeholder="请输入设备名称" class="layui-input" >
    </div>
</div>
<div class="layui-form-item">
    <label for="" class="layui-form-label">设备型号</label>
    <div class="layui-input-inline">
        <select name="classify_id" lay-search  lay-filter="classify_id">
            <option value="">全部</option>
            @foreach($cates as $first)
                <option value="{{ $first->id }}" @if(isset($plants->classify_id)&&$plants->classify_id==$first->id) selected @endif>{{ $first->category_title }}</option>
            @endforeach
        </select>
    </div>
</div>
<div class="layui-form-item">
    <label for="" class="layui-form-label">设备编号</label>
    <div class="layui-input-inline">
        <input type="text" name="plant_num" value="{{ $plants->plant_num ?? old('plant_num') }}" lay-verify="required" placeholder="请输入设备型号" class="layui-input" >
    </div>
</div>
<div class="layui-form-item">
    <label for="" class="layui-form-label">设备模块</label>
    <div class="layui-input-inline">
        <select name="module_id" lay-search  lay-filter="module_id">
            <option value="">全部</option>
            @foreach($module as $first)
                <option value="{{ $first->id }}" @if(isset($plants->module_id)&&$plants->module_id==$first->id) selected @endif>{{ $first->module_title }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="layui-form-item">
    <label for="" class="layui-form-label">排序</label>
    <div class="layui-input-inline">
        <input type="number" name="sort" value="{{ $plants->sort ?? 0 }}" lay-verify="required|number" placeholder="请输入数字" class="layui-input" >
        <input type="hidden" name="id" value="{{ $plants->id ?? 0 }}"  class="layui-input" >
    </div>
</div>
<!-- <div class="text-center"
             style="position: fixed;bottom: 0;left: 0;right: 0;background-color: #fff;box-shadow: 0 -1px 5px rgba(0,0,0,.15);padding: 15px;">
             <button class="layui-btn layui-btn-primary" type="button" id="closeFormBtn" ew-event="closePageDialog">取消</button>
           
            <button class="layui-btn layui-btn-primary" type="reset">重置表单</button>
            <button class="layui-btn" lay-filter="submitDemo" lay-submit>表单验证</button>
</div> -->


