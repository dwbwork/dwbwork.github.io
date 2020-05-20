@extends('admin.base')

@section('content')
    <div class="layui-card">
        <div class="layui-card-header layuiadmin-card-header-auto">

            <div class="layui-btn-group ">

                @can('internet.plant.create')
           <button id="btnAddRole" class="layui-btn icon-btn"><i class="layui-icon">&#xe654;</i>添加设备</button>

                @endcan
            </div>
        </div>
        <div class="layui-card-body">
            <table id="dataTable" lay-filter="dataTable"></table>
            <script type="text/html" id="options">
                <div class="layui-btn-group">
                    @can('internet.plant.edit')
                        <a class="layui-btn layui-btn-sm" lay-event="edit">编辑</a>
                    @endcan
                    @can('internet.plant.destroy')
                        <a class="layui-btn layui-btn-danger layui-btn-sm" lay-event="del">删除</a>
                    @endcan
                </div>
            </script>
        </div>
        <script type="text/html" id="category">
            @{{ d.category.category_title }}
        </script>
    </div>
@endsection

@section('script')
    <script type="text/javascript" src="/static/public/assets/js/common.js?v=315"></script>
    @can('internet.plant')
        <script>
           layui.use(['layer', 'table', 'form','admin', 'treetable'], function () {
                var $ = layui.jquery;
                var layer = layui.layer;
                var form = layui.form;
                var table = layui.table;
                var admin = layui.admin;
                var treetable = layui.table;
                //用户表格初始化

                // 渲染表格
                var dataTable = function () {
                  treetable.render({
                        elem: '#dataTable'
                       ,height: 500
                       ,page: true //开启分页
                        ,url: "{{ route('admin.plant.data') }}",
                        cols: [[ //表头
                            {field: 'id', title: 'ID', sort: true, width: 80}
                            , {field: 'plant_title', title: '设备型号'}
                            , {field: 'plant_num', title: '设备编号'}
                            , {field: 'classify_id', title: '设备型号',toolbar: '#category'}
                            , {field: 'module_id', title: '设备模块'}
                            , {field: 'sort', title: '序号'}
                            , {field: 'created_at', title: '创建时间'}
                            , {fixed: 'right', align: 'center', title: '操作',toolbar: '#options'}
                        ]]
                    });
                }
                //调用此函数可重新渲染表格
               dataTable();


               // 添加按钮点击事件
               $('#btnAddRole').click(function () {
                   showEditModel('新增设备',"{{route('admin.plant.create')}}");
               });

               //监听工具条
                table.on('tool(dataTable)', function (obj) { //注：tool是工具条事件名，dataTable是table原始容器的属性 lay-filter="对应的值"
                    var data = obj.data //获得当前行数据
                        , layEvent = obj.event; //获得 lay-event 对应的值
                    if (layEvent === 'del') {
                        layer.confirm('确认删除吗？', function (index) {
                            layer.close(index)
                            var load = layer.load();
                            $.post("{{ route('admin.plant.destroy') }}", {
                                _method: 'delete',
                                ids: [data.id]
                            }, function (res) {
                                layer.close(load);
                                if (res.code == 0) {
                                    layer.msg(res.msg, {icon: 1,time:1000}, function () {
                                        obj.del();
                                    })
                                } else {
                                    layer.msg(res.msg, {icon: 2})
                                }
                            });
                        });
                    } else if (layEvent === 'edit') {

                        showEditModel('编辑设备','/admin/plant/' + data.id + '/edit');

                    }
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
                                   if (res.code == 200) {
                                       layer.msg(res.msg, {icon: 1});
                                       layer.close(index);
                                       dataTable();
                                       //dataTable.reload({page: {curr: 1}});

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


            })
        </script>
    @endcan
@endsection