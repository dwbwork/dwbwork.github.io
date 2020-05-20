@extends('admin.base')

@section('content')
    <div class="layui-card">
        <div class="layui-card-header layuiadmin-card-header-auto">
            <div class="layui-btn-group">
                @can('message.journ.create')
                    <a href="{{route('admin.journ.create')}}">
                        <button class="layui-btn icon-btn">
                            <i class="layui-icon">&#xe654;</i>添加资讯
                        </button>
                    </a>
                @endcan
            </div>
        </div>


        <div class="layui-card-body">
            <table id="dataTable" lay-filter="dataTable"></table>

            <script type="text/html" id="options">
                <div class="layui-btn-group">
                    @can('message.journ.edit')
                        <a class="layui-btn layui-btn-sm" lay-event="edit">编辑</a>
                    @endcan
                    @can('message.journ.destroy')
                        <a class="layui-btn layui-btn-danger layui-btn-sm" lay-event="del">删除</a>
                    @endcan
                </div>
            </script>

        </div>

    </div>
    <script type="text/html" id="image">
        @{{#  if(d.url_list){ }}
        <a href="@{{d.url_list}}" target="_blank" title="点击查看">
            <img src="@{{d.url_list}}" alt="" width="50" height="50">
        </a>
        @{{#  } }}
    </script>
    <script type="text/html" id="tableStatusUser">
        <input type="checkbox" lay-filter="ckStatusUser" value="@{{d.id}}" lay-skin="switch"
               lay-text="显示|不显示" @{{d.status==1?'checked':''}}/>
    </script>
@endsection

@section('script')
    <script type="text/javascript" src="/static/public/assets/js/common.js?v=315"></script>
    @can('message.journ')
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
                        ,url: "{{route('admin.journ.data')}}" //数据接口
                        ,page: true //开启分页
                        ,cols: [[ //表头
                            {checkbox: true,fixed: true}
                            ,{field: 'id', title: 'ID', sort: true,width:80}
                            ,{field: 'title', title: '标题名称'}
                            ,{field: 'url_list', title: '主图',align: 'center', toolbar: '#image'}
                            ,{
                                title: '首页是否显示', templet: function (d) {
                                    var strs = ['<span class="layui-badge layui-bg-blue">不显示</span>',
                                        '<span class="layui-badge layui-bg-green">显示</span>',
                                        ];
                                    return strs[d.status];
                                }, align: 'center', width: 100
                             }
                            ,{field: 'status', sort: true, templet: '#tableStatusUser',width: 100, title: '状态操作'}
                            ,{field: 'created_at', title: '创建时间',sort: true}
                            ,{field: 'updated_at', title: '更新时间',sort: true}
                            ,{fixed: 'right', width: 220, align:'center', title: '操作',toolbar: '#options'}
                        ]]
                    });
                }

                dataTable()
                // 添加按钮点击事件
                $('#btnAddRole').click(function () {
                    showEditModel('新增',"{{route('admin.journ.create')}}");
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
                //监听工具条
                table.on('tool(dataTable)', function(obj){ //注：tool是工具条事件名，dataTable是table原始容器的属性 lay-filter="对应的值"
                    var data = obj.data //获得当前行数据
                        ,layEvent = obj.event; //获得 lay-event 对应的值
                    if(layEvent === 'del'){
                        layer.confirm('确认删除吗？', function(index){
                            $.post("{{route('admin.journ.destroy')}}",{_method:'delete',ids:data.id},function (result) {
                                if (result.code==0){
                                    obj.del(); //删除对应行（tr）的DOM结构
                                }
                                layer.close(index);
                                layer.msg(result.msg)
                            });
                        });
                    } else if(layEvent === 'edit'){
                      
                        location.href = '/admin/journ/'+data.id+'/edit';
                    }
                });
                form.on('switch(ckStatusUser)', function (obj) {
                    layer.load(2);
                    $.post("{{ route('admin.journ.action') }}", {
                        id: obj.elem.value,
                        status: obj.elem.checked ? 1 : 0
                    }, function (res) {
                        layer.closeAll('loading');
                        if (res.code == 200) {
                            layer.msg(res.msg, {icon: 1})
                            dataTable();
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