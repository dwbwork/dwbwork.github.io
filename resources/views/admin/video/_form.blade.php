{{csrf_field()}}
<div class="layui-form-item">
    <label for="" class="layui-form-label">标题</label>
    <div class="layui-input-inline">
        <input type="text" style="width: 380px" name="title" value="{{ $info->title ?? old('title') }}" lay-verify="required" placeholder="请输入标题" class="layui-input" >
    </div>
</div>
<div class="layui-form-item">
    <label for="" class="layui-form-label">分类</label>
    <div class="layui-input-inline">
        <select name="cate_id" lay-search  lay-filter="parent_id">
            <option value="0">请选择</option>
            @foreach($categories as $first)
                <option value="{{ $first->id }}" @if(isset($info->cate_id)&&$info->cate_id==$first->id) selected @endif>{{ $first->name }}</option>

            @endforeach
        </select>
    </div>
</div>
<input type="hidden" name="type" value="1">
<div class="layui-form-item">
                                    <label for="" class="layui-form-label">主图视频： </label>
                                    <div class="layui-input-block">
                                        <div class="layui-upload">
                                            <p> <span style="color: red ">视频限制 4M</span></p>
                                            <button type="button" class="layui-btn layui-btn-sm uploadPic"><i class="layui-icon">&#xe67c;</i>视频上传</button>
                                            <div class="layui-upload-list" >
                                                <ul class="layui-upload-box layui-clear">
                                                    @if(isset($info->url_list))
                                                    <li >
                                                       <video  id='video' src="{{$info->url_list}}" width="500" height="240" controls autobuffer></video>
                                                   </li>
                                                    @endif
                                                </ul>
                                                <input type="hidden" name="url_list" class="layui-upload-input" value="{{ $info->url_list??'' }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
<div class="layui-form-item">
    <label class="layui-form-label">视频详情：</label>
    <div class="layui-input-block">
        <textarea name="content" id="qaContent"> 
            {!! $info['content']??old('content') !!} 
        </textarea>
    </div>
</div>

<div class="layui-form-item">
    <label for="" class="layui-form-label">排序</label>
    <div class="layui-input-inline">
        <input type="number" name="sort_id" value="{{ $info->sort_id ?? 0 }}" lay-verify="required|number" placeholder="请输入数字" class="layui-input" >
    </div>
</div>



<div class="layer-footer" style="position: fixed; bottom: 0;width:100%;height:50px">
<div class="layui-form-item">
        <div class="layui-input-block text-center">
            
            <a class="layui-btn layui-btn-primary" href="{{route('admin.video')}}" >返 回</a>
            <button class="layui-btn" lay-filter="formSubmitAQ" lay-submit>&emsp;提交&emsp;</button>
        </div>
</div>
</div>
