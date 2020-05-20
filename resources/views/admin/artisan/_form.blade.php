{{csrf_field()}}


<div class="layui-form-item">
    <label for="" class="layui-form-label">控制器名</label>
    <div class="layui-input-block">
        <input type="text" name="controller" value="" required placeholder="请输入控制器名 例如：Admin/TestController" class="layui-input" >
    </div>
</div>

<div class="layui-form-item">
    <label for="" class="layui-form-label">模型名</label>
    <div class="layui-input-block">
        <input type="text" name="model" value=""required placeholder="请输入模型名例如：Models/Test" class="layui-input" >
    </div>
</div>


<div class="layui-form-item">
    <div class="layui-input-block">
        <button type="submit" class="layui-btn" lay-submit="" lay-filter="formDemo">确 认</button>
        
    </div>
</div>