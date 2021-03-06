@extends('admin.base')

@section('content')
    <div class="layui-card">

        <div class="layui-card-header layuiadmin-card-header-auto">
            <div class="layui-btn-group">
                @can('system.user.destroy')
             <button type="button" class="layui-btn layui-btn-danger" id="listDelete"><i class="layui-icon"></i>删除</button>

                @endcan
                @can('system.user.create')

                <button id="btnAddRole" class="layui-btn icon-btn"><i class="layui-icon">&#xe654;</i>添加</button>

                @endcan
            </div>


        </div>

        <div class="layui-card-body">
            <table id="dataTable" lay-filter="dataTable"></table>
            <script type="text/html" id="options">
                <div class="layui-btn-group">
                    @can('system.user.create')
                        <a class="layui-btn layui-btn-sm" lay-event="edit">编辑</a>
                    @endcan
                    @can('system.user.role')
                        <a class="layui-btn layui-btn-sm" lay-event="role">角色</a>
                    @endcan
                    @can('system.user.permission')
                        <a class="layui-btn layui-btn-sm" lay-event="permission">权限</a>
                    @endcan
                    @can('system.user.destroy')
                        <a class="layui-btn layui-btn-danger layui-btn-sm " lay-event="del">删除</a>
                    @endcan
                </div>
            </script>
        </div>

    </div>
@endsection

@section('script')
    <script type="text/javascript" src="/static/public/assets/js/common.js?v=315"></script>
    @can('system.user')
        <script>
            layui.use(['layer', 'table','admin', 'form'], function () {
                var $ = layui.jquery;
                var layer = layui.layer;
                var form = layui.form;
                var admin = layui.admin;
                var table = layui.table;

                //用户表格初始化
                var dataTable = function(){
                    table.render({
                        elem: '#dataTable'
                        , height: 500
                        , url: "{{ route('admin.user.data') }}"
                        , page: true //开启分页
                        , cols: [[ //表头
                            {checkbox: true, fixed: true}
                            , {field: 'id', title: 'ID', sort: true, width: 80}
                            , {field: 'username', title: '用户名'}
                            , {field: 'nickname', title: '昵称'}
                            , {field: 'email', title: '邮箱'}
                            , {field: 'phone', title: '电话'}
                            , {field: 'created_at', title: '创建时间'}
                            , {field: 'updated_at', title: '更新时间'}
                            , {fixed: 'right', width: 320, align: 'center', toolbar: '#options'}
                        ]]
                    });
                }
                 dataTable()
                //监听工具条
                table.on('tool(dataTable)', function (obj) { //注：tool是工具条事件名，dataTable是table原始容器的属性 lay-filter="对应的值"
                    var data = obj.data //获得当前行数据
                        , layEvent = obj.event; //获得 lay-event 对应的值
                    if (layEvent === 'del') {
                        layer.confirm('确认删除吗？', function (index) {
                            layer.close(index);
                            var load = layer.load();
                            $.post("{{ route('admin.user.destroy') }}", {
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
                        showEditModel('编辑用户','/admin/user/' + data.id + '/edit');
                        //location.href = '/admin/user/' + data.id + '/edit';
                    } else if (layEvent === 'role') {
                        showEditModel('编辑角色','/admin/user/' + data.id + '/role');
                        //location.href = '/admin/user/' + data.id + '/role';
                    } else if (layEvent === 'permission') {
                        showEditModel('编辑权限','/admin/user/' + data.id + '/permission');
                       // location.href = '/admin/user/' + data.id + '/permission';
                    }
                });

                // 添加按钮点击事件
                $('#btnAddRole').click(function () {
                    showEditModel('新增用户',"{{route('admin.user.create')}}");
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


                //按钮批量删除
                $("#listDelete").click(function () {
                    var ids = [];
                    var hasCheck = table.checkStatus('dataTable');
                    var hasCheckData = hasCheck.data;
                    if (hasCheckData.length > 0) {
                        $.each(hasCheckData, function (index, element) {
                            ids.push(element.id)
                        })
                    }
                    if (ids.length > 0) {
                        layer.confirm('确认删除吗？', function (index) {
                            layer.close(index);
                            var load = layer.load();
                            $.post("{{ route('admin.user.destroy') }}", {
                                _method: 'delete',
                                ids: [data.id]
                            }, function (res) {
                                layer.close(load);
                                if (res.code == 0) {
                                    layer.msg(res.msg, {icon: 1}, function () {
                                        dataTable.reload({page: {curr: 1}});
                                    })
                                } else {
                                    layer.msg(res.msg, {icon: 2})
                                }
                            });
                        })
                    } else {
                        layer.msg('请选择删除项', {icon: 2})
                    }
                })
            })
        </script>
    @endcan
@endsection



