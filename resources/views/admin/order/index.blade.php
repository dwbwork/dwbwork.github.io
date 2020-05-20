@extends('admin.base')

@section('content')

<style type="text/css">
    .layui-table-cell {
        height: 80px;
        line-height: 80px;
        }
        th .layui-table-cell {
        height: 30px;
        line-height: 30px;
        }
        th .laytable-cell-1-0-7 {
        height: 30px;
        line-height: 30px;
        }
        .layui-table img {
        width: 100px;
        height: 60px;
        }
    
}
</style>
<!-- 正文开始 -->
<div class="layui-fluid">
    <div class="layui-card">
        <div class="layui-card-body">
            <div class="layui-form toolbar">
<blockquote class="layui-elem-quote layui-quote-nm">
              订单状态：
              <button type="button" class="layui-btn layui-btn-primary layui-btn-sm">全部<span class="layui-badge layui-bg-gray">1</span></button>
              <button type="button" class="layui-btn layui-btn-primary layui-btn-sm">待支付<span class="layui-badge layui-bg-orange">1</span></button>
              <button type="button" class="layui-btn layui-btn-primary layui-btn-sm">已支付,待发货<span class="layui-badge">1</span></button>
              <button type="button" class="layui-btn layui-btn-primary layui-btn-sm">待收货<span class="layui-badge layui-bg-gray">1</span></button>
              <button type="button" class="layui-btn layui-btn-primary layui-btn-sm">已完成<span class="layui-badge layui-bg-gray">1</span></button>
             
              
              <br><br>订单类型：
              <button type="button" class="layui-btn layui-btn-primary layui-btn-sm">全部<span class="layui-badge layui-bg-gray">1</span></button>
              <button type="button" class="layui-btn layui-btn-primary layui-btn-sm">普通订单<span class="layui-badge layui-bg-orange">1</span></button>
              <button type="button" class="layui-btn layui-btn-primary layui-btn-sm">拼团订单<span class="layui-badge layui-bg-gray">1</span></button>
              <button type="button" class="layui-btn layui-btn-primary layui-btn-sm">秒杀订单<span class="layui-badge layui-bg-gray">1</span></button>
              <button type="button" class="layui-btn layui-btn-primary layui-btn-sm">砍价订单<span class="layui-badge layui-bg-gray">1</span></button>
              <button type="button" class="layui-btn layui-btn-primary layui-btn-sm">积分订单<span class="layui-badge layui-bg-gray">1</span></button>
              
              
              <br><br>支付方式：
              <button type="button" class="layui-btn layui-btn-primary layui-btn-sm">全部<span class="layui-badge layui-bg-gray">1</span></button>
              <button type="button" class="layui-btn layui-btn-primary layui-btn-sm">微信支付<span class="layui-badge layui-bg-gray">1</span></button>
              <button type="button" class="layui-btn layui-btn-primary layui-btn-sm">支付宝支付<span class="layui-badge layui-bg-gray">1</span></button>
              <button type="button" class="layui-btn layui-btn-primary layui-btn-sm">余额支付<span class="layui-badge layui-bg-gray">1</span></button>
              <button type="button" class="layui-btn layui-btn-primary layui-btn-sm">线下支付<span class="layui-badge layui-bg-gray">1</span></button>

              <br><br>创建时间：
               <button type="button" class="layui-btn layui-btn-primary layui-btn-sm">全部</button>
               <button type="button" class="layui-btn layui-btn-primary layui-btn-sm">今天</button>
               <button type="button" class="layui-btn layui-btn-primary layui-btn-sm">昨天</button>
               <button type="button" class="layui-btn layui-btn-primary layui-btn-sm">最近一星期</button>
               <button type="button" class="layui-btn layui-btn-primary layui-btn-sm">本月</button>
               <button type="button" class="layui-btn layui-btn-primary layui-btn-sm">自&nbsp;定&nbsp;义</button>
               <div class="layui-input-inline">
                    <input type="text" class="layui-input" id="test10" placeholder="&nbsp;&nbsp;&nbsp;  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;选择时间  ">
               </div>
               
               
</blockquote>
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label">订&nbsp;&nbsp;单&nbsp;&nbsp;名：</label>
                        <div class="layui-input-inline">
                            <input name="goods_no" value="{{@$search->order_sn}}" class="layui-input" type="text" placeholder="输入订单号"/>
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
                        <button class="layui-btn icon-btn " lay-filter="formSubSearchTbAdv" lay-submit>
                            <i class="layui-icon">&#xe615;</i>搜索
                        </button>
                        @can('item.item.create')
                         <a href="{{route('admin.item.create')}}">
                        <button class="layui-btn icon-btn">  
                            
                                <i class="layui-icon">&#xe605;</i>订单核销
                        </button></a>
                        @endcan
                        
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
    layui.use(['layer', 'form', 'table', 'util', 'laydate','dropdown', 'element'], function () {
        var $ = layui.jquery;
        var layer = layui.layer;
        var form = layui.form;
        var table = layui.table;
        var util = layui.util;
        var laydate = layui.laydate;
        var dropdown = layui.dropdown;
        var element = layui.element;
        form.render('select');
        //日期时间范围
          laydate.render({
            elem: '#test10'
            ,range: true
          });

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