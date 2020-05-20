@extends('admin.base')

@section('content')
    <div class="layui-card">
        <div class="layui-card-header layuiadmin-card-header-auto">
            <div class="layui-btn-group">
                @can('message.video.create')
                    <a href="{{route('admin.video.create')}}">
                        <button class="layui-btn icon-btn">
                            <i class="layui-icon">&#xe654;</i>添加视频
                        </button>
                    </a>
                @endcan
            </div>
        </div>

        <script type="text/html" id="tableStatusUser">
                <input type="checkbox" lay-filter="ckStatusUser" value="@{{d.id}}" lay-skin="switch"
                       lay-text="置顶|非置顶" @{{d.status==1?'checked':''}}/>
        </script>
        <div class="layui-card-body">
            <table id="dataTable" lay-filter="dataTable"></table>
            <script type="text/html" id="options">
                <div class="layui-btn-group">
                    @can('message.video.edit')
                        <a class="layui-btn layui-btn-sm" lay-event="edit">编辑</a>
                    @endcan
                    @can('message.video.destroy')
                        <a class="layui-btn layui-btn-danger layui-btn-sm" lay-event="del">删除</a>
                    @endcan
                </div>
            </script>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript" src="/static/public/assets/js/common.js?v=315"></script>
    @can('message.video')
        <script>
            layui.use(['layer','table','admin','form'],function () {
                var layer = layui.layer;
                var $ = layui.jquery;
                var form = layui.form;
                var admin = layui.admin;
                var table = layui.table;
                //用户表格初始化
                var dataTable = function(){
                    table.render({
                        elem: '#dataTable'
                        ,height: 500
                        ,url: "{{route('admin.video.data')}}" //数据接口
                        ,page: true //开启分页
                        ,cols: [[ //表头
                            {checkbox: true,fixed: true}
                            ,{field: 'id', title: 'ID', sort: true,width:80}
                           
                            ,{field: 'title', title: '视频名称'}
                             
                            ,{field: 'created_at', title: '创建时间',sort: true}
                            ,{field: 'updated_at', title: '更新时间',sort: true}
                            ,{
                            title: '状态', templet: function (d) {
                            var strs = ['<span class="layui-badge layui-bg-green">不显示</span>','<span class="layui-badge layui-bg-blue">显  示</span>'];
                                    return strs[d.status];
                                }, align: 'center', width: 100
                            }
                ,{field: 'status', sort: true, templet: '#tableStatusUser',width: 100, title: '上下架操作'}
                             ,{field: 'sort_id', title: '排序',sort: true,width:80}
                            ,{fixed: 'right', width: 220, align:'center', toolbar: '#options'}
                        ]]
                    });
                }

                dataTable()
                // 添加按钮点击事件
                $('#btnAddRole').click(function () {
                    showEditModel('新增',"{{route('admin.video.create')}}");
                });
               
                //监听工具条
                table.on('tool(dataTable)', function(obj){ //注：tool是工具条事件名，dataTable是table原始容器的属性 lay-filter="对应的值"
                    var data = obj.data //获得当前行数据
                        ,layEvent = obj.event; //获得 lay-event 对应的值
                    if(layEvent === 'del'){
                        layer.confirm('确认删除吗？', function(index){
                            $.post("{{route('admin.video.destroy')}}",{_method:'delete',ids:[data.id]},function (result) {
                                if (result.code==0){
                                    obj.del(); //删除对应行（tr）的DOM结构
                                }
                                layer.close(index);
                                layer.msg(result.msg)
                            });
                        });
                    } else if(layEvent === 'edit'){
                       location.href = '/admin/video/'+data.id+'/edit';
                        //location.href = '/admin/position/'+data.id+'/edit';
                    }
                });


                form.on('switch(ckStatusUser)', function (obj) {
                    layer.load(2);
                    $.post("{{ route('admin.video.action') }}", {
                        id: obj.elem.value,
                        status: obj.elem.checked ? 1 : 0
                    }, function (res) {
                        layer.closeAll('loading');
                        if (res.code == 200) {
                            layer.msg(res.msg, {icon: 1})
                             dataTable()
                        } else {
                            layer.msg(res.msg, {icon: 2});
                            $(obj.elem).prop('checked', !obj.elem.checked);
                            form.render('checkbox');
                        }
                    }, 'json');
        });
            });
        </script>
    @endcan
@endsection