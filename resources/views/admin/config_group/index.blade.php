@extends('admin.base')

@section('content')
    <div class="layui-card">
        <div class="layui-card-header layuiadmin-card-header-auto">
            <div class="layui-btn-group ">
                @can('system.config_group.create')
                <a href="{{route('admin.config_group.create')}}"><button id="btnAddRole" class="layui-btn icon-btn"><i class="layui-icon">&#xe654;</i>添加</button>
                </a>

                @endcan

            </div>
        </div>
        <div class="layui-card-body">
            <table id="dataTable" lay-filter="dataTable"></table>
            <script type="text/html" id="options">
                <div class="layui-btn-group">
                    @can('system.config_group.edit')
                        <a class="layui-btn layui-btn-sm" lay-event="edit">编辑</a>
                    @endcan
                    @can('system.config_group.destroy')
                        <a class="layui-btn layui-btn-danger layui-btn-sm" lay-event="del">删除</a>
                    @endcan
                </div>
            </script>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript" src="/static/public/assets/js/common.js?v=315"></script>
    @can('system.config_group')
        <script>
            layui.use(['layer', 'table','admin', 'form'], function () {
                var $ = layui.jquery;
                var layer = layui.layer;
                var form = layui.form;
                var admin = layui.admin;
                var table = layui.table;
                //用户表格初始化
                var dataTable =function(){

                   table.render({
                        elem: '#dataTable'
                        , autoSort: false
                        , height: 500
                        , url: "{{ route('admin.config_group.data') }}" //数据接口
                        , page: true //开启分页
                        , cols: [[ //表头
                            {checkbox: true, fixed: true}
                            , {field: 'id', title: 'ID', sort: true, width: 80}
                            , {field: 'name', title: '分类名称'}
                            , {field: 'sort', sort: true, title: '排序'}
                            , {field: 'created_at', title: '创建时间'}
                            , {field: 'updated_at', title: '更新时间'}
                            , {fixed: 'right', width: 220, align: 'center', toolbar: '#options'}
                        ]]
                    }); }
                dataTable();
                // 添加按钮点击事件
                $('#btnAddRole').click(function () {
                    showEditModel('新增',"{{route('admin.role.create')}}");
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
                table.on('tool(dataTable)', function (obj) { //注：tool是工具条事件名，dataTable是table原始容器的属性 lay-filter="对应的值"
                    var data = obj.data //获得当前行数据
                        , layEvent = obj.event; //获得 lay-event 对应的值
                    if (layEvent === 'del') {
                        layer.confirm('确认删除吗？', function (index) {
                            layer.close(index);
                            var load = layer.load();
                            $.post("{{ route('admin.config_group.destroy') }}", {
                                _method: 'delete',
                                ids: [data.id]
                            }, function (res) {
                                layer.close(load);
                                if (res.code == 0) {
                                    layer.msg(res.msg, {icon: 1}, function () {
                                        obj.del();
                                    })
                                } else {
                                    layer.msg(res.msg, {icon: 2})
                                }
                            });
                        });
                    } else if (layEvent === 'edit') {
                        //location.href = '/admin/config_group/' + data.id + '/edit';
                        showEditModel('编辑', '/admin/config_group/' + data.id + '/edit');
                    }
                });
            })
        </script>
    @endcan
@endsection