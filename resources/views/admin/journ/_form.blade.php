{{csrf_field()}}
<div class="layui-form-item">
    <label for="" class="layui-form-label">标题</label>
    <div class="layui-input-inline">
        <input type="text" style="width: 380px" name="title" value="{{ $journ->title ?? old('title') }}" lay-verify="required" placeholder="请输入标题" class="layui-input" >
    </div>
</div>
<div class="layui-form-item">
    <label for="" class="layui-form-label">主图：</label>
    <div class="layui-input-block">
        <div class="layui-upload">
            <button type="button" class="layui-btn layui-btn-sm uploadPic"><i class="layui-icon">&#xe67c;</i>图片上传</button>
            <div class="layui-upload-list" >
                <ul class="layui-upload-box layui-clear">
                    @if(isset($journ['url_list']))
                        <li><img style="width:100px;height:60px" src="{{ $journ['url_list'] }}" /></li>
                    @endif
                </ul>
                <input type="hidden" name="url_list" class="layui-upload-input" value="{{ $journ['url_list']??'' }}">
            </div>
        </div>
    </div>
</div>
<div class="layui-form-item">
    <label class="layui-form-label">资讯详情：</label>
    <div class="layui-input-block">
        <textarea name="content" id="qaContent">
            {!! @$journ['content']??old('content') !!}
        </textarea>
    </div>
</div>

<div class="layui-form-item">
    <label for="" class="layui-form-label">排序</label>
    <div class="layui-input-inline">
        <input type="number" name="sort_id" value="{{ $journ->sort_id ?? 0 }}" lay-verify="required|number" placeholder="请输入数字" class="layui-input" >
    </div>
</div>
<div class="layui-form-item">
    <div class="layui-input-block">
        <button type="submit" class="layui-btn" lay-submit="" lay-filter="formDemo">确 认</button>
        <a  class="layui-btn" href="{{route('admin.journ')}}" >返 回</a>
    </div>
</div>
