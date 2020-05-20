{{csrf_field()}}


<div class="layui-form-item">
    <label for="" class="layui-form-label">合作商名称</label>
    <div class="layui-input-inline">
        <input type="text" name="name" value="{{ $info->name ?? old('name') }}" lay-verify="required" placeholder="请输入图标名称" class="layui-input" >
    </div>
</div>



<div class="layui-form-item">
    <label for="" class="layui-form-label">排序</label>
    <div class="layui-input-inline">
        <input type="number" name="sort" value="{{ $info->sort ?? 0 }}" lay-verify="required|number" placeholder="请输入数字" class="layui-input" >
    </div>
</div>

<div class="layui-form-item layui-form-text">
    <label class="layui-form-label">合作商简介</label>
    <div class="layui-input-block">
        <textarea placeholder="请输入简介" class="layui-textarea" name="description" required>{{ $info->description ?? 0 }}</textarea>
    </div>
</div>



