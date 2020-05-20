@extends('admin.base')

@section('content')
    <div class="layui-card">
        <div class="layui-card-header layuiadmin-card-header-auto">
            <h2>添加模块</h2>
        </div>
        <div class="layui-card-body">
            <form class="layui-form" action="{{route('admin.artisan.store')}}" method="post" >
                @include('admin.artisan._form')
            </form>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
         function newTab(url,tit){
        if(top.layui.index){
            top.layui.index.openTabsPage(url,tit)
        }else{
            window.open(url)
        }
    }

    layui.config({
        base: '/static/admin/layuiadmin/' //静态资源所在路径
    }).extend({
        index: 'lib/index' //主入口模块
    }).use(['layer'],function () {
        var $ = layui.jquery;
        var layer = layui.layer;

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        //错误提示
        @if(count($errors)>0)
            @foreach($errors->all() as $error)
                layer.msg("{{$error}}",{icon:2});
                @break
            @endforeach
        @endif

        //一次性正确信息提示
        @if(session('success'))
            layer.msg("{{session('success')}}",{icon:1});
        @endif

        //一次性错误信息提示
        @if(session('error'))
        layer.msg("{{session('error')}}",{icon:2});
        @endif

    });
    </script>
@endsection
