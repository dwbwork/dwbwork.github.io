@extends('admin.base')

@section('content')
    <div class="layui-card">
        <div class="layui-card-header layuiadmin-card-header-auto">
            <div class="layui-btn-group">
                @can('system.configuration.create')

               <button id="btnAddRole" class="layui-btn icon-btn"><i class="layui-icon">&#xe654;</i>添加</button>

                @endcan
            </div>
        </div>
        <div class="layui-card-body">
            <div class="layui-tab layui-tab-brief" lay-filter="docDemoTabBrief">
                <ul class="layui-tab-title">
                    @foreach($groups as $group)
                        <li @if($loop->index==0) class="layui-this" @endif >{{$group->name}}</li>
                    @endforeach
                </ul>
                <div class="layui-tab-content">
                    @foreach($groups as $group)
                        <div class="layui-tab-item @if($loop->index==0) layui-show @endif">
                            <form class="layui-form">
                                @foreach($group->configurations as $configuration)
                                    <div class="layui-form-item">
                                        <label for="" class="layui-form-label" style="width: 120px">{{$configuration->label}}</label>
                                        <div class="layui-input-inline" style="min-width: 600px">
                                            @switch($configuration->type)
                                                @case('input')
                                                    <input type="input" class="layui-input" name="{{$configuration->key}}" value="{{$configuration->val}}">
                                                    @break
                                                @case('textarea')
                                                    <textarea name="{{$configuration->key}}" class="layui-textarea">{{$configuration->val}}</textarea>
                                                    @break
                                                @case('select')
                                                    <select name="{{$configuration->key}}">
                                                        @if($configuration->content)
                                                            @foreach(explode("|",$configuration->content) as $v)
                                                                @php
                                                                    $key = \Illuminate\Support\Str::before($v,':');
                                                                    $val = \Illuminate\Support\Str::after($v,':');
                                                                @endphp
                                                                <option value="{{$key}}" @if($key==$configuration->val) selected @endif >{{$val}}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                    @break
                                                @case('radio')
                                                    @if($configuration->content)
                                                        @foreach(explode("|",$configuration->content) as $v)
                                                            @php
                                                                $key = \Illuminate\Support\Str::before($v,':');
                                                                $val = \Illuminate\Support\Str::after($v,':');
                                                            @endphp
                                                            <input type="radio" name="{{$configuration->key}}" value="{{$key}}" @if($key==$configuration->val) checked @endif title="{{$val}}">
                                                        @endforeach
                                                    @endif
                                                    @break
                                                @case('image')
                                                    <div class="layui-upload">
                                                        <button type="button" class="layui-btn layui-btn-sm uploadPic"><i class="layui-icon">&#xe67c;</i>图片上传</button>
                                                        <div class="layui-upload-list" >
                                                            <ul class="layui-upload-box layui-clear">
                                                                @if($configuration->val)
                                                                    <li style="width:100px;background-color: #24262f"><img style="width:100px;height:100px" src="{{ $configuration->val }}" /></li>
                                                                @endif
                                                            </ul>
                                                            <input type="hidden" class="layui-upload-input" name="{{$configuration->key}}" value="{{$configuration->val}}">
                                                        </div>
                                                    </div>
                                                    @break
                                                @default
                                                    @break
                                            @endswitch
                                        </div>
                                        <div class="layui-form-mid layui-word-aux">{{$configuration->tips}}</div>
                                    </div>
                                @endforeach
                                    <div class="layui-form-item">
                                        <div class="layui-input-block">
                                            <button type="submit" class="layui-btn" lay-submit lay-filter="config_group">确 认</button>
                                        </div>
                                    </div>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript" src="/static/public/assets/js/common.js?v=315"></script>
    @can('system.configuration')
        <script>
            layui.use(['layer', 'table', 'form','admin','upload','element'], function () {
                var $ = layui.jquery;
                var layer = layui.layer;
                var form = layui.form;
                var admin = layui.admin;
                var table = layui.table;
                var upload = layui.upload;

                //图片
                $(".uploadPic").each(function (index,elem) {
                    upload.render({
                        elem: $(elem)
                        ,url: '{{ route("api.upload") }}'
                        ,multiple: false
                        ,data:{"_token":"{{ csrf_token() }}"}
                        ,done: function(res){
                            //如果上传失败
                            if(res.code == 0){
                                layer.msg(res.msg,{icon:1},function () {
                                    $(elem).parent('.layui-upload').find('.layui-upload-box').html('<li style="width:100px;background-color: #24262f"><img style="width:100px;height:100px" src="'+res.url+'" /></li>');
                                    $(elem).parent('.layui-upload').find('.layui-upload-input').val(res.url);
                                })
                            }else {
                                layer.msg(res.msg,{icon:2,time:1000})
                            }
                        }
                    });
                })
                // 添加按钮点击事件
                $('#btnAddRole').click(function () {
                    showEditModel('新增',"{{route('admin.configuration.create')}}");
                });
                // 显示表单弹窗
                function showEditModel(title,url) {
                    layer.full(admin.open({
                        type: 2,

                        title: title,
                        content: url,
                        btn:['提交','取消'],
                        btnAlign: 'c',   // 按钮居中
                        success: function (layero, dIndex) {
                            form.render();    // 表单渲染
                        },

                        yes: function (index, layero) {
                            // 获取弹出层中的form表单元素
                        
                            // 获取弹出层中的form表单元素
                            var formSubmit=layer.getChildFrame('form', index);
                            //获取表单数据
                            var data = {dosubmit:1};
                            var action = formSubmit[0]['action'];
                            var a = formSubmit.serializeArray();

                        
                            $.each(a, function () {
                                if (data[this.name] !== undefined) {
                                    if (!data[this.name].push) {
                                        data[this.name] = [data[this.name]];
                                    }
                                    data[this.name].push(this.value || '');
                                } else {
                                    data[this.name] = this.value || '';
                                }
                            });

                            if (data.parent_id == '') {
                                data.parent_id = '0';
                            }

                            layer.load(2);
                            $.post(action, data, function (res) {
                                layer.closeAll('loading');
                                if (res.code == 0) {
                                    layer.msg(res.msg, {icon: 1});
                                    layer.close(index);

                                    dataTable()

                                } else {
                                    layer.msg(res.msg, {icon: 2});
                                }
                            }, 'json');

                            return false;

                        },
                        btn2: function (index, layero) {
                            layer.close(index);
                        }


                    }));
                }
                //提交
                form.on('submit(config_group)',function (data) {
                    var parm = data.field;
                    parm['_method'] = 'put';
                    var load = layer.load();
                    $.post("{{route('admin.configuration.update')}}",data.field,function (res) {
                        layer.close(load);
                        if (res.code==0){
                            layer.msg(res.msg,{icon:1})
                        }else {
                            layer.msg(res.msg,{icon:2});
                        }
                    });
                    return false;
                });
            })
        </script>
    @endcan
@endsection