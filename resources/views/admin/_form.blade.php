{{csrf_field()}}
<!-- <div class="layui-form-item">
    <label for="" class="layui-form-label">广告位置</label>
    <div class="layui-input-inline" >
        <select name="position_id"  lay-verify="required" id="position_id" lay-filter="position_id" >
            <option value=""></option>
            
        </select>


    </div>

</div> -->

<div class="layui-form-item">
    <label for="" class="layui-form-label">图标名称</label>
    <div class="layui-input-inline">
        <input type="text" name="name" value="{{ $info->name ?? old('name') }}" lay-verify="required" placeholder="请输入图标名称" class="layui-input" >
    </div>
</div>


<div class="layui-form-item">
    <label for="" class="layui-form-label">图标</label>
    <p id="process"> <span style="color: red ">图片比例为 宽375px * 高667px </span></p>
    <div class="layui-input-block">
        <div class="layui-upload">
            <button type="button" class="layui-btn layui-btn-sm uploadPic"><i class="layui-icon">&#xe67c;</i>图片上传</button>
            <div class="layui-upload-list" >
                <ul class="layui-upload-box layui-clear">
                    @if(isset($info->thumb))
                        <li><img style="width:100px;height:60px" src="{{ $info->thumb }}" /></li>
                    @endif
                </ul>
                <input type="hidden" name="thumb" class="layui-upload-input" value="{{ $info->thumb??'' }}">
            </div>
        </div>
    </div>
</div>
<div class="layui-form-item">
    <label for="" class="layui-form-label">排序</label>
    <div class="layui-input-inline">
        <input type="number" name="sort" value="{{ $info->sort ?? 0 }}" lay-verify="required|number" placeholder="请输入数字" class="layui-input" >
    </div>
</div>
<!-- <div class="layui-form-item">
    <label for="" class="layui-form-label">类型</label>
    <div class="layui-input-inline" >
        <select name="type" lay-verify="required" id="position_id" lay-filter="position_id" >
            <option value="1"  @if(@$advert->type == 1 ) selected @endif>机构</option>
            <option value="2" @if(@$advert->type == 2 ) selected @endif>资讯</option>

            <option id="net"  @if (!in_array(@$advert->position_id,[1,4])) disabled  @endif value="3" @if(@$advert->type == 3 ) selected @endif>网上课程</option>

        </select>


    </div>

</div> 
<div class="layui-form-item">
    <label for="" class="layui-form-label">绑定跳转ID</label>
    <div class="layui-input-inline">
        <input type="number" name="bind_id" value="{{ $advert->bind_id ?? 0 }}" lay-verify="required|number" placeholder="请输入数字" class="layui-input" >
    </div>
</div>-->
<div class="layui-form-item">
    <label for="" class="layui-form-label">链接</label>
    <div class="layui-input-inline">
        <input type="text" name="link" value="{{ $info->link ?? '' }}" placeholder="请输入链接地址" class="layui-input" >
    </div>
    <div class="layui-form-mid"><span class="layui-word-aux">格式：http://xxxxx</span></div>
</div>


