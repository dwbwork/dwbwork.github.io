{{csrf_field()}}
<div class="layui-form-item">
    <label for="" class="layui-form-label">广告位置</label>
    <div class="layui-input-inline" >
        <select name="position_id"  lay-verify="required" id="position_id" lay-filter="position_id" >
            <option value=""></option>
            @foreach($positions as $position)
                <option value="{{ $position->id }}" {{ $position->selected??'' }} >{{ $position->name }}</option>
            @endforeach
        </select>


    </div>

</div>

<div class="layui-form-item">
    <label for="" class="layui-form-label">广告标题</label>
    <div class="layui-input-inline">
        <input type="text" name="title" value="{{ $advert->title ?? old('name') }}" lay-verify="required" placeholder="请输入标题" class="layui-input" >
    </div>
</div>


<div class="layui-form-item">
    <label for="" class="layui-form-label">主图</label>
    <p id="process" @if(@$advert->position_id !=4 )style="display: none" @endif> <span style="color: red ">图片比例为 宽375px * 高667px </span></p>
    <div class="layui-input-block">
        <div class="layui-upload">
            <button type="button" class="layui-btn layui-btn-sm uploadPic"><i class="layui-icon">&#xe67c;</i>图片上传</button>
            <div class="layui-upload-list" >
                <ul class="layui-upload-box layui-clear">
                    @if(isset($advert->thumb))
                        <li><img style="width:100px;height:60px" src="{{ $advert->thumb }}" /></li>
                    @endif
                </ul>
                <input type="hidden" name="thumb" class="layui-upload-input" value="{{ $advert->thumb??'' }}">
            </div>
        </div>
    </div>
</div>
<div class="layui-form-item">
    <label for="" class="layui-form-label">排序</label>
    <div class="layui-input-inline">
        <input type="number" name="sort" value="{{ $advert->sort ?? 0 }}" lay-verify="required|number" placeholder="请输入数字" class="layui-input" >
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
        <input type="text" name="link" value="{{ $advert->link ?? '' }}" placeholder="请输入链接地址" class="layui-input" >
    </div>
    <div class="layui-form-mid"><span class="layui-word-aux">格式：http://xxxxx</span></div>
</div>
{{--<div class="layui-form-item">
    <label for="" class="layui-form-label">描述</label>
    <div class="layui-input-inline">
        <textarea name="description" style="width: 400px;" placeholder="请输入描述" class="layui-textarea">{{$advert->description??old('description')}}</textarea>
    </div>
</div>--}}
{{--

<div class="layui-form-item">
    <div class="layui-input-block">
        <button type="submit" class="layui-btn" lay-submit="" lay-filter="formDemo">确 认</button>
        <a  class="layui-btn" href="{{route('admin.advert')}}" >返 回</a>
    </div>
</div>--}}
