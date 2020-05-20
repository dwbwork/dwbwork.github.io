@extends('admin.base')


@section('content')
<!-- 正文开始 -->
<div class="layui-fluid">
    <div class="layui-card">
        <div class="layui-card-body">
            <div class="layui-form toolbar">
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label">货&emsp;&emsp;号：</label>
                        <div class="layui-input-inline">
                            <input name="goods_no" value="{{@$search->goods_no}}" class="layui-input" type="text" placeholder="输入货号"/>
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">商&nbsp;&nbsp;品&nbsp;&nbsp;名：</label>
                        <div class="layui-input-inline">
                            <input name="goods_name" value="{{@$search->goods_name}}" class="layui-input" type="text" placeholder="输入商品名"/>
                        </div>
                    </div>
                    
                    <div class="layui-inline form-search-show-expand">
                        <label class="layui-form-label">状&emsp;&emsp;态：</label>
                        <div class="layui-input-inline">
                            <select name="goods_status">
                                <option value="" selected>所有</option>
                                <option value="1" @if((isset($search) && $search['goods_status']==1)) selected @endif>正常</option>
                                <option value="2" @if((isset($search) && $search['goods_status']==2)) selected @endif>下架</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="layui-inline" style="padding-left: 20px;">
                        <button class="layui-btn icon-btn" lay-filter="formSubSearchTbAdv" lay-submit>
                            <i class="layui-icon">&#xe615;</i>搜索
                        </button>

                         @can('item.item.create')
                         <a href="{{route('admin.item.create')}}">
                        <button class="layui-btn icon-btn">  
                            
                                <i class="layui-icon">&#xe654;</i>发布商品 
                        </button></a>
                        @endcan
                        <button id="btnExportTbBas" class="layui-btn icon-btn">
                            <i class="layui-icon">&#xe67d;</i>导出
                        </button>
                        
                    </div>
                </div>
            </div>
            <table id="tableTbAdv" lay-filter="tableTbAdv"></table>
            <script type="text/html" id="thumb">
                @{{#  if(d.thumb){ }}
                <a href="@{{d.thumb}}" target="_blank" title="点击查看">
                    <img src="@{{d.thumb}}" alt="" width="50" height="50">
                </a>
                @{{#  } }}
            </script>
            <script type="text/html" id="category">
                @{{ d.category.name }}
            </script>
            <script type="text/html" id="tableStatusUser">
                <input type="checkbox" lay-filter="ckStatusUser" value="@{{d.id}}" lay-skin="switch"
                       lay-text="上架|下架" @{{d.goods_status==1?'checked':''}}/>
            </script>
            <script type="text/html" id="options">
                <div class="layui-btn-group">
                    @can('item.item.edit')
                        <a class="layui-btn layui-btn-sm" lay-event="edit">编辑</a>
                    @endcan
                    @can('item.item.destroy')
                        <a class="layui-btn layui-btn-danger layui-btn-sm" lay-event="del">删除</a>
                    @endcan
                </div>
            </script>
        </div>
    </div>

</div>

@endsection

@section('script')
    <script type="text/javascript" src="/static/public/assets/js/common.js?v=315"></script>
    @can('item.item')
        <script>
    layui.use(['layer', 'form', 'table', 'util', 'laydate'], function () {
        var $ = layui.jquery;
        var layer = layui.layer;
        var form = layui.form;
        var table = layui.table;
        var util = layui.util;
        var laydate = layui.laydate;

        form.render('select');

        // 渲染表格
        var insTb = table.render({
            elem: '#tableTbAdv',
            url: "{{route('admin.item.data')}}",
            page: true
            ,height: 600,
            cols: [[
                {type: 'checkbox'},
                {field: 'goods_name', align: 'center', sort: true, title: '商品名称'},
                {field: 'thumb', title: '主图',align: 'center', toolbar: '#thumb'},
                {field: 'goods_no', align: 'center', sort: true, title: '货号'},
                {field: 'category_id', title: '分类', toolbar: '#category'},
                
                {
                    field: 'createTime', sort: true, align: 'center', templet: function (d) {
                        return util.toDateString(d.createTime);
                    }, title: '创建时间'
                },
                {
                            title: '状态', templet: function (d) {
                            var strs = ['<span class="layui-badge layui-bg-blue">待审核</span>','<span class="layui-badge layui-bg-green">上架</span>', '<span class="layui-badge layui-bg-red">下架</span>'];
                                    return strs[d.goods_status];
                                }, align: 'center', width: 100
                            }
                ,{field: 'goods_status', sort: true, templet: '#tableStatusUser',width: 100, title: '上下架操作'}
                
                , {fixed: 'right', width: 120, align: 'center', title: '操作',toolbar: '#options'}
            ]]
        });
        //监听工具条
                table.on('tool(tableTbAdv)', function (obj) { //注：tool是工具条事件名，dataTable是table原始容器的属性 lay-filter="对应的值"
                    var data = obj.data //获得当前行数据
                        , layEvent = obj.event; //获得 lay-event 对应的值
                    if (layEvent === 'del') {
                        layer.confirm('确认删除吗？', function (index) {
                            layer.close(index);
                            var load = layer.load();
                            $.post("{{ route('admin.item.destroy') }}", {
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
                        location.href = '/admin/item/' + data.id + '/edit';
                    }
                });

        // 搜索
        form.on('submit(formSubSearchTbAdv)', function (data) {
            insTb.reload({where: data.field}, 'data');
        });

        // 导出excel
        $('#btnExportTbBas').click(function () {
            var checkRows = table.checkStatus('tableTbAdv');
            
            if (checkRows.data.length == 0) {
                layer.msg('请选择要导出的数据', {icon: 2});
            } else {
                table.exportFile(insTb.config.id, checkRows.data, 'xls');
            }
        });
        form.on('switch(ckStatusUser)', function (obj) {
                    layer.load(2);
                    $.post("{{ route('admin.item.action') }}", {
                        id: obj.elem.value,
                        goods_status: obj.elem.checked ? 1 : 2
                    }, function (res) {
                        layer.closeAll('loading');
                        if (res.code == 200) {
                            layer.msg(res.msg, {icon: 1})
                            insTb.reload();
                        } else {
                            layer.msg(res.msg, {icon: 2});
                            $(obj.elem).prop('checked', !obj.elem.checked);
                            form.render('checkbox');
                        }
                    }, 'json');
        });
        // 渲染laydate
        laydate.render({
            elem: '#edtDateTbAdv'
        });

    });
</script>
    @endcan
@endsection