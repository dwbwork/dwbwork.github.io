{{csrf_field()}}

<div class="layui-form-item">
    <label for="" class="layui-form-label">上级分类</label>
    <div class="layui-input-inline">
        <select name="parent_id" lay-search  lay-filter="parent_id">
            <option value="0">一级分类</option>
            @foreach($categories as $first)
                <option value="{{ $first->id }}" @if(isset($info->parent_id)&&$info->parent_id==$first->id) selected @endif>{{ $first->name }}</option>
                @if($first->childs->isNotEmpty())
                    @foreach($first->childs as $second)
                        <option value="{{ $second->id }}" {{ isset($info->id) && $info->parent_id==$second->id ? 'selected' : '' }}>┗━━{{$second->name}}</option>
                    @endforeach
                @endif
            @endforeach
        </select>
    </div>
</div>

<div class="layui-form-item">
    <label for="" class="layui-form-label">名称</label>
    <div class="layui-input-inline">
        <input type="text" name="name" value="{{ $info->name ?? old('name') }}" lay-verify="required" placeholder="请输入名称" class="layui-input" >
    </div>
</div>
<div class="layui-form-item">
    <label for="" class="layui-form-label">排序</label>
    <div class="layui-input-inline">
        <input type="number" name="sort" value="{{ $info->sort ?? 10 }}" lay-verify="required|number" placeholder="请输入数字" class="layui-input" >
        <input type="hidden" name="id" value="{{ $info->id ?? 0 }}"  class="layui-input" >
    </div>
</div>
<!-- <div class="text-center"
             style="position: fixed;bottom: 0;left: 0;right: 0;background-color: #fff;box-shadow: 0 -1px 5px rgba(0,0,0,.15);padding: 15px;">
             <button class="layui-btn layui-btn-primary" type="button" id="closeFormBtn" ew-event="closePageDialog">取消</button>
           
            <button class="layui-btn layui-btn-primary" type="reset">重置表单</button>
            <button class="layui-btn" lay-filter="submitDemo" lay-submit>表单验证</button>
</div> -->


