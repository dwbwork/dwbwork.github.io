@extends('admin.base')

@section('content')
    <div class="layui-card">
        <div class="layui-card-header layuiadmin-card-header-auto">

            <div class="layui-form">
               {{-- <div class="layui-btn-group ">

                    <button class="layui-btn layui-btn-sm layui-btn-danger" id="listDelete">删除</button>

                    <button class="layui-btn layui-btn-sm" id="memberSearch">搜索</button>
                </div>--}}
                <div class="layui-input-inline">
                    <input type="text" name="name" id="name" placeholder="请输入昵称" class="layui-input">
                </div>
                <div class="layui-input-inline">
                    <input type="text" name="phone" id="phone" placeholder="请输入手机号" class="layui-input">
                </div>
                <button type="button" id="memberSearch" class="layui-btn icon-btn" lay-submit lay-filter="search" ><i class="layui-icon">&#xe615;</i>搜 索</button>
            </div>
        </div>
        <div class="layui-card-body">
            <table id="dataTable" lay-filter="dataTable"></table>
            
            <script type="text/html" id="avatar">
                <a href="@{{d.avatar_url}}" target="_blank" title="点击查看"><img src="@{{d.avatar_url}}" alt="" width="28" height="28"></a>
            </script>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript" src="/static/public/assets/js/common.js?v=315"></script>
    @can('member.member')
        <script>
            layui.use(['layer','table','form'],function () {
                var layer = layui.layer;
                var $ = layui.jquery;
                var form = layui.form;
                var table = layui.table;
                //用户表格初始化
                var dataTable = table.render({
                    elem: '#dataTable'
                    ,height: 500
                    ,url: "{{ route('admin.member.data') }}" //数据接口
                    ,where:{model:"member"}
                    ,page: true //开启分页
                    ,cols: [[ //表头
                        {checkbox: true,fixed: true}
                        ,{field: 'id', title: 'ID', sort: true,width:80}
                        ,{field: 'avatar_url', title: '头像',toolbar:'#avatar',width:100}
                        ,{field: 'nick_name', title: '昵称'}
                        ,{field: 'phone', title: '手机'}
                       
                        ,{field: 'created_at', title: '创建时间'}
                        ,{field: 'updated_at', title: '更新时间'}
                       /* ,{fixed: 'right', width: 120, align:'center', toolbar: '#options'}*/
                    ]]
                });

               

               
                //搜索
                $("#memberSearch").click(function () {
                    var userSign = $("#user_sign").val()
                    var name = $("#name").val();
                    var phone = $("#phone").val();
                    dataTable.reload({
                        where:{user_sign:userSign,name:name,phone:phone},
                        page:{curr:1}
                    })
                })
            })
        </script>
    @endcan
@endsection



