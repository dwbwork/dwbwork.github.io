@extends('admin.base')

@section('content')
    <div class="layui-card">
        <div class="layui-card-header layuiadmin-card-header-auto">

            <div class="layui-btn-group ">

                @can('item_category.item_category.create')
           <button id="btnAddRole" class="layui-btn icon-btn"><i class="layui-icon">&#xe654;</i>添加</button>

                @endcan
            </div>
        </div>
        <div class="layui-card-body">
            <table id="dataTable" lay-filter="dataTable"></table>
            <script type="text/html" id="options">
                <div class="layui-btn-group">
                    @can('item_category.item_category.edit')
                        <a class="layui-btn layui-btn-sm" lay-event="edit">编辑</a>
                    @endcan
                    @can('item_category.item_category.destroy')
                        <a class="layui-btn layui-btn-danger layui-btn-sm" lay-event="del">删除</a>
                    @endcan
                </div>
            </script>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript" src="/static/public/assets/js/common.js?v=315"></script>
    @can('item_category.item_category')
        <script>
           layui.use(['layer', 'table', 'form','admin', 'treetable'], function () {
                var $ = layui.jquery;
                var layer = layui.layer;
                var form = layui.form;
                var table = layui.table;
                var admin = layui.admin;
                var treetable = layui.treetable;
                //用户表格初始化

                // 渲染表格
                var dataTable = function () {
                  treetable.render({
                        treeColIndex: 1,          // treetable新增参数
                        treeSpid: 0,             // treetable新增参数
                        treeIdName: 'id',       // treetable新增参数
                        treePidName: 'parent_id',     // treetable新增参数
                        treeDefaultClose: false,   // treetable新增参数
                        treeLinkage: false,        // treetable新增参数
                        elem: '#dataTable',
                        url: "{{ route('admin.item_category.data') }}",
                        cols: [[ //表头
                            {field: 'id', title: 'ID', sort: true, width: 80}
                            , {field: 'name', title: '名称'}
                            , {field: 'sort', title: '序号'}
                            , {field: 'created_at', title: '创建时间'}
                            , {field: 'updated_at', title: '更新时间'}
                            , {fixed: 'right', width: 260, align: 'center', toolbar: '#options'}
                        ]]
                    });
                }
                //调用此函数可重新渲染表格
               dataTable();


               // 添加按钮点击事件
               $('#btnAddRole').click(function () {
                   showEditModel('新增分类',"{{route('admin.item_category.create')}}");
               });

               //监听工具条
                table.on('tool(dataTable)', function (obj) { //注：tool是工具条事件名，dataTable是table原始容器的属性 lay-filter="对应的值"
                    var data = obj.data //获得当前行数据
                        , layEvent = obj.event; //获得 lay-event 对应的值
                    if (layEvent === 'del') {
                        layer.confirm('确认删除吗？', function (index) {
                            layer.close(index)
                            var load = layer.load();
                            $.post("{{ route('admin.item_category.destroy') }}", {
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

                        showEditModel('编辑分类','/admin/item_category/' + data.id + '/edit');

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